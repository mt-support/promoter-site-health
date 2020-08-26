<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Firebase\JWT\JWT;
use Tribe\Extensions\Promoter\Health\Critical_Exception;
use Tribe__Promoter__Connector;

class Connection implements Runnable {
	/**
	 * @var Tribe__Promoter__Connector
	 */
	private $connector;
	/**
	 * @var array|\WP_Error
	 */
	private $response;
	/**
	 * @var string
	 */
	private $body;
	/**
	 * @var int
	 */
	private $code;
	/**
	 * @var string
	 */
	private $token = '';

	public function run() {
		$this->connector = tribe( 'promoter.connector' );

		if ( ! $this->connector instanceof Tribe__Promoter__Connector ) {
			throw new Critical_Exception( 'The connector class is not defined.' );
		}

		$license_info = tribe( 'promoter.pue' )->get_license_info();

		if ( empty( $license_info['key'] ) ) {
			throw new Critical_Exception( 'The license key of promoter is not present.' );
		}

		$payload = [
			'licenseKey' => $license_info['key'],
		];

		$this->token = JWT::encode( $payload, get_option( 'tribe_promoter_auth_key' ) );
		$this->request();

		if ( is_wp_error( $this->response ) ) {
			throw new Critical_Exception(
				'Error while connecting to the connector application, your site is not able to communicate with the connector site.'
			);
		}

		if ( $this->code < 200 || $this->code >= 300 ) {
			$message = sprintf(
				"<p>Response: %s - Code: %s</p><p>Token: <pre>%s</pre></p><p>%s</p>",
				$this->body,
				$this->code,
				$this->token,
				"This means that your site can't communicate correctly with WordPress, try to refresh the connection."
			);

			$actions = sprintf( '<p><a href="%s" target="_blank" rel="noopener noreferrer">Authorize Again</a></p>', esc_url( 'https://promoter.theeventscalendar.com/authorization' ) );

			throw new Critical_Exception( $message, $actions );
		}

		$this->validate_user();
	}

	private function request() {
		$this->response = wp_remote_post( $this->connector->base_url() . 'connect/auth', [
			'body'      => [ 'token' => $this->token ],
			'timeout'   => 30,
			'sslverify' => false,
		] );

		$this->code = (int) wp_remote_retrieve_response_code( $this->response );
		$this->body = wp_remote_retrieve_body( $this->response );
	}

	/**
	 * @throws Critical_Exception
	 */
	private function validate_user() {
		$user_id = (int) $this->body;
		$actions = sprintf( '<p><a href="%s" target="_blank" rel="noopener noreferrer">Authorize Again</a></p>', esc_url( 'https://promoter.theeventscalendar.com/authorization' ) );

		if ( $user_id === 0 ) {
			throw new Critical_Exception(
				'<p>Looks like the User ID registered it was not set correctly, as is zero. </p>',
				$actions
			);
		}

		$user = get_user_by( 'id', $user_id );

		if ( false === $user ) {
			throw new Critical_Exception(
				"<p>The user with the ID: <strong>{$user_id}</strong> does not exists on this WordPress installation, make sure to use a valid user.</p>",
				$actions
			);
		}

		if ( ! user_can( $user, 'read_private_posts' ) ) {
			throw new Critical_Exception(
				"<p>The user <pre>{$user->user_email}</pre> Does not have enough permissions to read private posts on this WordPress installation.</p>",
				$actions
			);
		}

		if ( ! user_can( $user, 'manage_options' ) ) {
			throw new Critical_Exception(
				"<p>The user <pre>{$user->user_email}</pre> Does not have enough permissions to manage options on this WordPress installation.</p>",
				$actions
			);
		}
	}
}

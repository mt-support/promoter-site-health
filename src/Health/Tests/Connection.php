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
			throw new Critical_Exception( __( 'The connector class is not defined.', 'promoter-site-health' ) );
		}

		$license_info = tribe( 'promoter.pue' )->get_license_info();

		if ( empty( $license_info['key'] ) ) {
			throw new Critical_Exception( __( 'The license key of promoter is not present.', 'promoter-site-health' ) );
		}

		$payload = [
			'licenseKey' => $license_info['key'],
		];

		$this->token = JWT::encode( $payload, get_option( 'tribe_promoter_auth_key' ) );
		$this->request();

		if ( is_wp_error( $this->response ) ) {
			throw new Critical_Exception(
				__( 'Error while connecting to the connector application, your site is not able to communicate with the connector site.', 'promoter-site-health' )
			);
		}

		if ( $this->code < 200 || $this->code >= 300 ) {
			$message = sprintf(
				"<p>%1$s: %2$s - %3$s: %4$s</p><p>%5$s: <pre>%6$s</pre></p><p>%7$s</p>",
				__( 'Response', 'promoter-site-health' ),
				$this->body,
				__( 'Code', 'promoter-site-health' ),
				$this->code,
				__( 'Token', 'promoter-site-health' ),
				$this->token,
				__( "This means that your site can't communicate correctly with WordPress, try to refresh the connection.", 'promoter-site-health' )
			);

			$actions = sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
				esc_url( 'https://promoter.theeventscalendar.com/authorization' ),
				__( 'Authorize Again', 'promoter-site-health' )
			);

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
		$actions = sprintf(
			'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
			esc_url( 'https://promoter.theeventscalendar.com/authorization' ),
			__( 'Authorize Again', 'promoter-site-health' )
		);

		if ( $user_id === 0 ) {
			throw new Critical_Exception(
				sprintf(
					"<p>%1$s</p>",
					__( 'Looks like the User ID registered it was not set correctly, as is zero.', 'promoter-site-health' )
				),
				$actions
			);
		}

		$user = get_user_by( 'id', $user_id );

		if ( false === $user ) {
			throw new Critical_Exception(
				sprintf(
					"<p>%1$s<strong>%2$s</strong>%3$s</p>",
					__( 'The user with the ID: ', 'promoter-site-health' ),
					$user_id,
					__( ' does not exists on this WordPress installation, make sure to use a valid user.', 'promoter-site-health' )
				),
				$actions
			);
		}

		if ( ! user_can( $user, 'read_private_posts' ) ) {
			throw new Critical_Exception(
				sprintf(
					"<p>%1$s<pre>%2$s</pre>%3$s</p>",
					__( 'The user ', 'promoter-site-health' ),
					$user->user_email,
					__( ' Does not have enough permissions to read private posts on this WordPress installation.', 'promoter-site-health' )
				),
				$actions
			);
		}

		if ( ! user_can( $user, 'manage_options' ) ) {
			throw new Critical_Exception(
				sprintf(
					"<p>%1$s<pre>%2$s</pre>%3$s</p>",
					__( 'The user ', 'promoter-site-health' ),
					$user->user_email,
					__( ' Does not have enough permissions to manage options on this WordPress installation.', 'promoter-site-health' )
				),
				$actions
			);
		}
	}
}

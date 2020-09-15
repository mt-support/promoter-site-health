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
			throw new Critical_Exception( esc_html__( 'The connector class is not defined.', 'promoter-site-health' ) );
		}

		$license_info = tribe( 'promoter.pue' )->get_license_info();

		if ( empty( $license_info['key'] ) ) {
			throw new Critical_Exception( esc_html__( 'The license key of promoter is not present.', 'promoter-site-health' ) );
		}

		$payload = [
			'licenseKey' => $license_info['key'],
		];

		$this->token = JWT::encode( $payload, get_option( 'tribe_promoter_auth_key' ) );
		$this->request();

		if ( is_wp_error( $this->response ) ) {
			throw new Critical_Exception(
				esc_html__( 'Error while connecting to the connector application, your site is not able to communicate with the connector site.', 'promoter-site-health' )
			);
		}

		if ( $this->code < 200 || $this->code >= 300 ) {
			$message = sprintf(
				'<p>%1$s: %s - %s: %s </p><p>%s: <pre>%s</pre></p><p>%s</p>',
				esc_html_x( 'Response', 'The HTTP response from the server', 'promoter-site-health' ),
				esc_html( $this->body ),
				esc_html_x( 'Code', 'The error code that was generated for this error', 'promoter-site-health' ),
				esc_html( $this->code ),
				esc_html_x( 'Token', 'The token string associated with this error', 'promoter-site-health' ),
				$this->token,
				esc_html__( "This means that your site can't communicate correctly with WordPress, try to refresh the connection.", 'promoter-site-health' )
			);

			$actions = sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
				esc_url( 'https://promoter.theeventscalendar.com/authorization' ),
				esc_html__( 'Authorize Again', 'promoter-site-health' )
			);

			throw new Critical_Exception( $message, $actions );
		}

		$this->validate_user();
	}

	private function request() {
		$this->response = wp_remote_post( $this->connector->base_url() . 'connect/auth', [
			'body'      => [ 'token' => $this->token ],
			'timeout'   => 30,
			'sslverify' => false, // SSL disabled to allow local URL to be pinged.
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
			esc_html__( 'Authorize Again', 'promoter-site-health' )
		);

		if ( $user_id === 0 ) {
			throw new Critical_Exception(
				sprintf(
					'<p>%1$s</p>',
					esc_html__( 'Looks like the User ID registered it was not set correctly, as is zero.', 'promoter-site-health' )
				),
				$actions
			);
		}

		$user = get_user_by( 'id', $user_id );

		if ( false === $user ) {
			throw new Critical_Exception(
				sprintf(
					'<p>%1$s<strong>%2$s</strong>%3$s</p>',
					esc_html__( 'The user with the ID: ', 'promoter-site-health' ),
					$user_id,
					esc_html__( ' does not exists on this WordPress installation, make sure to use a valid user.', 'promoter-site-health' )
				),
				$actions
			);
		}

		if ( ! user_can( $user, 'read_private_posts' ) ) {
			throw new Critical_Exception(
				sprintf(
					'<p>%1$s<pre>%2$s</pre>%3$s</p>',
					esc_html__( 'The user ', 'promoter-site-health' ),
					$user->user_email,
					esc_html__( ' Does not have enough permissions to read private posts on this WordPress installation.', 'promoter-site-health' )
				),
				$actions
			);
		}

		if ( ! user_can( $user, 'manage_options' ) ) {
			throw new Critical_Exception(
				sprintf(
					'<p>%1$s<pre>%2$s</pre>%3$s</p>',
					esc_html__( 'The user ', 'promoter-site-health' ),
					$user->user_email,
					esc_html__( ' Does not have enough permissions to manage options on this WordPress installation.', 'promoter-site-health' )
				),
				$actions
			);
		}
	}
}

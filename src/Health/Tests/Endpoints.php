<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;
use Tribe__Promoter__Connector;

class Endpoints implements Runnable {

	public function run() {
		$this->connector = tribe( 'promoter.connector' );

		if ( ! $this->connector instanceof Tribe__Promoter__Connector ) {
			throw new Critical_Exception( esc_html__( 'The connector class is not defined.', 'promoter-site-health' ) );
		}

		$endpoints = [
			get_rest_url( get_current_blog_id(), 'tribe/events/v1/events' ),
			get_rest_url( get_current_blog_id(), 'tribe/tickets/v1/tickets' ),
		];

		foreach ( $endpoints as $endpoint ) {
			$response = wp_remote_get( $endpoint, [
				'timeout'   => 30,
				'sslverify' => false, // SSL disabled to allow local URL to be pinged.
			] );

			if ( is_wp_error( $response ) ) {
				throw new Critical_Exception(
					sprintf(
						esc_html__( "The endpoint: '%1$s' is not returning a valid response.", 'promoter-site-health' ),
						$endpoint
					)
				);
			}

			$json = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $json === null || empty( $json ) ) {
				throw new Critical_Exception(
					sprintf(
						esc_html__( "The endpoint: '%1$s' does not return a valid JSON response.", 'promoter-site-health' ),
						$endpoint
					)
				);
			}

			$code = (int) wp_remote_retrieve_response_code( $response );

			if ( $code < 200 || $code >= 300 ) {
				throw new Critical_Exception(
					sprintf(
						esc_html__( "The endpoint: '%1$s' is not reachable, make sure it returns a valid response.", 'promoter-site-health' ),
						$endpoint
					)
				);
			}
		}
	}
}

<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;
use Tribe__Promoter__Connector;

class Endpoints implements Runnable {

	public function run() {
		$this->connector = tribe( 'promoter.connector' );

		if ( ! $this->connector instanceof Tribe__Promoter__Connector ) {
			throw new Critical_Exception( 'The connector class is not defined.' );
		}

		$endpoints = [
			get_rest_url( get_current_blog_id(), 'tribe/events/v1/events' ),
			get_rest_url( get_current_blog_id(), 'tribe/tickets/v1/tickets' ),
		];

		foreach ( $endpoints as $endpoint ) {
			$response = wp_remote_get( $endpoint, [
				'timeout'   => 30,
				'sslverify' => false,
			] );

			if ( is_wp_error( $response ) ) {
				throw new Critical_Exception(
					"The endpoint: '{$endpoint}' is not returning a valid response."
				);
			}

			$json = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $json === null || empty( $json ) ) {
				throw new Critical_Exception(
					"The endpoint: '{$endpoint}' does not return a valid JSON response."
				);
			}

			$code = (int) wp_remote_retrieve_response_code( $response );

			if ( $code < 200 || $code >= 300 ) {
				throw new Critical_Exception(
					"The endpoint: '{$endpoint}' is not reachable, make sure it returns a valid response."
				);
			}
		}
	}
}

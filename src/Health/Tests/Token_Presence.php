<?php

namespace TEC\Extensions\Promoter\Health\Tests;

use TEC\Extensions\Promoter\Health\Critical_Exception;

class Token_Presence implements Runnable {
	public function run() {
		$option = get_option( 'tribe_promoter_auth_key' );
		$action = sprintf(
			'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
			esc_url_raw( 'https://promoter.theeventscalendar.com/authorization' ),
			esc_html__( 'Authorize again', 'promoter-site-health' )
		);

		if ( $option === null ) {
			throw new Critical_Exception(
				esc_html__( 'The token for promoter does not exist on this installation. Make sure to complete the onboarding process.', 'promoter-site-health' ),
				$action
			);
		}

		$option = trim( $option );

		if ( empty( $option ) ) {
			throw new Critical_Exception(
				esc_html__( 'The token for Promoter exists but is empty. Make sure to complete the onboarding process.', 'promoter-site-health' ),
				$action
			);
		}
	}
}

<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class Token_Presence implements Runnable {
	public function run() {
		$option = get_option( 'tribe_promoter_auth_key' );
		$action = sprintf(
			'<p><a href="%s" target="_blank" rel="noopener noreferrer">Authorize Again</a></p>',
			esc_url( 'https://promoter.theeventscalendar.com/authorization' )
		);

		if ( $option === null ) {
			throw new Critical_Exception(
				'The token for promoter does not exist on this installation. Make sure to complete the onboarding process.',
				$action
			);
		}

		$option = trim( $option );

		if ( empty( $option ) ) {
			throw new Critical_Exception(
				'The token for Promoter exists but is empty. Make sure to complete the onboarding process.',
				$action
			);
		}
	}
}

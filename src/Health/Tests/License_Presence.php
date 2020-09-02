<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;
use Tribe\Extensions\Promoter\Health\Recommended_Exception;
use Tribe__Promoter__PUE;

class License_Presence implements Runnable {
	public function run() {
		/** @var Tribe__Promoter__PUE $pue */
		$pue = tribe( 'promoter.pue' );

		if ( ! $pue instanceof Tribe__Promoter__PUE ) {
			throw new Critical_Exception(
				__( 'Looks like the Tribe Plugin Update Engine (PUE) is not available on this WordPress installation.', 'promoter-site-health' )
			);
		}

		if ( $pue->has_license_key() ) {
			return;
		}

		throw new Recommended_Exception(
			'Make sure a Promoter license key exists.',
			sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2%s</a>%3$s</p>',
				esc_url( 'https://theeventscalendar.com/my-account/' ),
				__( 'Visit your account', 'promoter-site-health' ),
				__( ' to find your license key', 'promoter-site-health' )
			)
		);
	}
}

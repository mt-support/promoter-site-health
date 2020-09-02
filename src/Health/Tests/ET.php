<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class ET implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Tickets__Main' ) ) {
			return;
		}

		throw new Critical_Exception(
			__( 'Make sure Event Tickets plugin is installed and activated on this WordPress installation', 'promoter-site-health' ),
			sprintf(
				'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>%3$s</p>',
				esc_url( 'https://wordpress.org/plugins/event-tickets/' ),
				__( 'Download ET', 'promoter-site-health' ),
				__( 'plugin', 'promoter-site-health' )
			)
		);
	}
}

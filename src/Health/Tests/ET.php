<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class ET implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Tickets__Main' ) ) {
			return;
		}

		throw new Critical_Exception(
			'Make sure Event Tickets plugin is installed and activated on this WordPress installation',
			sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">Download ET</a> plugin</p>',
				esc_url( 'https://wordpress.org/plugins/event-tickets/' )
			)
		);
	}
}

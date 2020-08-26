<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class TEC implements Runnable {
	public function run() {
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			return;
		}

		throw new Critical_Exception(
			'Make sure The Events Calendar plugin is installed and activated on this WordPress installation',
			sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">Download The Events Calendar</a> plugin</p>',
				esc_url( 'https://wordpress.org/plugins/the-events-calendar/' )
			)
		);
	}
}

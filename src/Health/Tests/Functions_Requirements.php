<?php

namespace TEC\Extensions\Promoter\Health\Tests;

use TEC\Extensions\Promoter\Health\Critical_Exception;

class Functions_Requirements implements Runnable {
	public function run() {
		if ( ! function_exists( 'tribe' ) ) {
			throw new Critical_Exception(
				esc_html__( "Make sure the function 'tribe' exists in order to execute the tests.", 'promoter-site-health' )
			);
		}
	}
}

<?php

namespace Tribe\Extensions\Promoter\Health\Tests;

use Tribe\Extensions\Promoter\Health\Critical_Exception;

class Functions_Requirements implements Runnable {
	public function run() {
		if ( ! function_exists( 'tribe' ) ) {
			throw new Critical_Exception(
				"Make sure the function 'tribe' exists in order to execute the tests."
			);
		}
	}
}

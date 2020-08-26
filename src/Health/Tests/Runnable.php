<?php


namespace Tribe\Extensions\Promoter\Health\Tests;


use Tribe\Extensions\Promoter\Health\Health_Exception;

interface Runnable {
	/**
	 * @throws Health_Exception
	 * @return void
	 */
	public function run();
}

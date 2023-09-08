<?php


namespace TEC\Extensions\Promoter\Health\Tests;


use TEC\Extensions\Promoter\Health\Health_Exception;

interface Runnable {
	/**
	 * @throws Health_Exception
	 * @return void
	 */
	public function run();
}

<?php


namespace Tribe\Extensions\Promoter\Health;


use Throwable;

interface Health_Exception extends Throwable {
	public function getActions();

	public function getColor();

	public function getStatus();
}

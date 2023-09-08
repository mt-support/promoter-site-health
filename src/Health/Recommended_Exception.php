<?php

namespace TEC\Extensions\Promoter\Health;

use Exception;
use Throwable;

class Recommended_Exception extends Exception implements Health_Exception {
	/**
	 * @var string
	 */
	private $actions;
	/**
	 * @var string
	 */
	private $color;
	/**
	 * @var string
	 */
	private $status;

	public function __construct( $message = '', $actions = '' ) {
		$this->actions = $actions;
		$this->color   = 'orange';
		$this->status  = 'recommended';

		parent::__construct( $message, 0, null );
	}

	public function getActions() {
		return $this->actions;
	}

	public function getColor() {
		return $this->color;
	}

	public function getStatus() {
		return $this->status;
	}
}

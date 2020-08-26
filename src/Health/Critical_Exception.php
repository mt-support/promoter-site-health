<?php

namespace Tribe\Extensions\Promoter\Health;

use Exception;

class Critical_Exception extends Exception implements Health_Exception {
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

	public function __construct( $message, $actions = '' ) {
		$this->actions = $actions;
		$this->color   = 'red';
		$this->status  = 'critical';

		parent::__construct( $message, 0, null );
	}

	public function getStatus() {
		return $this->status;
	}

	public function getActions() {
		return $this->actions;
	}

	public function getColor() {
		return $this->color;
	}
}

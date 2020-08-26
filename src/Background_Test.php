<?php


namespace Tribe\Extensions\Promoter;


use Tribe\Extensions\Promoter\Health\Health_Exception;
use Tribe\Extensions\Promoter\Health\Tests\Connection;
use Tribe\Extensions\Promoter\Health\Tests\Endpoints;
use Tribe\Extensions\Promoter\Health\Tests\ET;
use Tribe\Extensions\Promoter\Health\Tests\Functions_Requirements;
use Tribe\Extensions\Promoter\Health\Tests\License_Presence;
use Tribe\Extensions\Promoter\Health\Tests\Runnable;
use Tribe\Extensions\Promoter\Health\Tests\TEC;
use Tribe\Extensions\Promoter\Health\Tests\Token_Presence;

class Background_Test {
	public static $tests = [
		TEC::class,
		ET::class,
		Endpoints::class,
		Functions_Requirements::class,
		License_Presence::class,
		Token_Presence::class,
		Connection::class,
	];

	public static function run_test() {
		check_ajax_referer( 'health-check-site-status' );

		$result = [
			'label'       => 'Promoter - Site Health Review',
			'completed'   => true,
			'status'      => 'good',
			'badge'       => [
				'label' => 'Promoter',
				'color' => 'blue',
			],
			'description' => 'Everything looks correctly in your WordPress installation.',
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">Visit your Account</a></p>', esc_url( 'https://promoter.theeventscalendar.com/' )
			),
			'test'        => 'promoter_auth_connection',
		];

		try {
			/** @var Runnable $test */
			foreach ( self::$tests as $test ) {
				( new $test() )->run();
			}
			wp_send_json_success( $result );
		} catch ( Health_Exception $exception ) {
			$result['status']         = $exception->getStatus();
			$result['badge']['color'] = $exception->getColor();
			$result['description']    = $exception->getMessage();
			$result['actions']        = $exception->getActions();
			$result['completed']      = false;
			wp_send_json_error( $result );
		}
	}
}

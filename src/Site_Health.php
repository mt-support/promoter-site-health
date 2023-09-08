<?php

namespace TEC\Extensions\Promoter;

class Site_Health {
	public static function add_status_test( array $tests ): array {
		$tests['async']['promoter_site_health'] = [
			'label'     => esc_html__( 'Promoter can communicate with the Connector App', 'promoter-site-health' ),
			'test'      => 'promoter_site_health',
			'completed' => false,
		];

		return $tests;
	}
}

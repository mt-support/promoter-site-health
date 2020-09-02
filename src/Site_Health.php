<?php

namespace Tribe\Extensions\Promoter;

class Site_Health {
	public static function add_status_test( array $tests ): array {
		$tests['async']['promoter_site_health'] = [
			'label'     => __( 'Promoter can communicate with the connector app', 'promoter-site-health' ),
			'test'      => 'promoter_site_health',
			'completed' => false,
		];

		return $tests;
	}
}

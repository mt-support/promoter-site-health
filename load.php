<?php
require_once __DIR__ . '/vendor/autoload.php';

use \TEC\Extensions\Promoter\Site_Health;
use \TEC\Extensions\Promoter\Background_Test;

add_filter( 'site_status_tests', [ Site_Health::class, 'add_status_test' ] );
add_action( 'wp_ajax_health-check-promoter-site_health', [ Background_Test::class, 'run_test' ] );
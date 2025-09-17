<?php

namespace Plan\Core;

defined( 'ABSPATH' ) || exit;

class Core {
	public function __construct() {
		new PlanCache();
		new Publish();
	}
}
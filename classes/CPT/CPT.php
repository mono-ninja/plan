<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;

class CPT {
	public function __construct() {
		new CPT_Plan();
		new General();
		new Features();
		new Status();
		new AdminColumns();
	}
}
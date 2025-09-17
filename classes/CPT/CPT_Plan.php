<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;

use Plan\Plan;

class CPT_Plan {
	public function __construct() {
		add_action( 'init', [ $this, 'register_cpt' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function register_cpt(): void {
		$icon      = $this->logo();
		$icon_data = 'data:image/svg+xml;base64,' . base64_encode( $icon );

		register_post_type( 'plan', [
			'label'        => __( 'Plans', 'plan' ),
			'public'       => false,
			'show_ui'      => true,
			'supports'     => [ 'title', 'page-attributes' ],
			'show_in_rest' => true,
			'menu_icon'    => $icon_data,
		] );
	}

	public function logo() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><title>restaurant-menu</title><g fill="#212121" stroke-linecap="square" stroke-linejoin="miter" stroke-miterlimit="10"><rect x="4" y="1" width="22" height="30" fill="none" stroke="#212121" stroke-width="2"></rect> <line x1="10" y1="20" x2="20" y2="20" fill="none" stroke="#212121" stroke-width="2"></line> <line x1="10" y1="25" x2="20" y2="25" fill="none" stroke="#212121" stroke-width="2"></line> <circle cx="15" cy="11" r="4" fill="none" stroke="#212121" stroke-width="2"></circle> <line x1="30" y1="5" x2="30" y2="27" fill="none" stroke="#212121" stroke-width="2"></line></g></svg>';
	}


	public function enqueue_scripts( $hook ): void {
		$screen = get_current_screen();
		if ( $screen && $screen->post_type === 'plan' && in_array( $screen->base, [
				'post',
				'post-new',
				'edit'
			], true ) ) {

			wp_enqueue_style( 'plan-admin', Plan::url() . 'assets/css/admin.css', [], Plan::info( 'version' ) );
			wp_enqueue_script( 'plan-admin', Plan::url() . 'assets/js/admin.js', [], Plan::info( 'version' ), true );
		}
	}

}
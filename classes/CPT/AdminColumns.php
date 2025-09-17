<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;
class AdminColumns {
	public function __construct() {
		add_filter( 'manage_plan_posts_columns', [ $this, 'add_columns' ] );
		add_action( 'manage_plan_posts_custom_column', [ $this, 'render_column' ], 10, 2 );
	}

	public function add_columns( array $columns ): array {
		$new = [];
		foreach ( $columns as $key => $title ) {
			$new[ $key ] = $title;
			if ( $key === 'title' ) {
				$new['is_enabled'] = __( 'Enabled', 'plan' );
				$new['is_annual']  = __( 'Annual', 'plan' );
				$new['is_starred'] = __( 'Recommended', 'plan' );
				$new['price']      = __( 'Price', 'plan' );
			}
		}
		return $new;
	}

	public function render_column( string $column, int $post_id ): void {
		switch ( $column ) {
			case 'is_enabled':
				echo get_post_meta( $post_id, 'is_enabled', true ) ? '✅' : '—';
				break;

			case 'is_annual':
				echo get_post_meta( $post_id, 'is_annual', true ) ? '📅' : '—';
				break;

			case 'is_starred':
				echo get_post_meta( $post_id, 'is_starred', true ) ? '⭐' : '—';
				break;

			case 'price':
				$price = get_post_meta( $post_id, 'price', true );
				$label = get_post_meta( $post_id, 'custom_price_label', true );
				echo $label ?: esc_html( $price );
				break;
		}
	}

}
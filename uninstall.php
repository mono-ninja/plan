<?php
/**
 * Uninstall script for Plan plugin
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_transient( 'plans_query_cache' );

$plans = get_posts( [
	'post_type'      => 'plan',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'fields'         => 'ids',
] );

if ( $plans ) {
	foreach ( $plans as $plan_id ) {
		delete_post_meta( $plan_id, 'price' );
		delete_post_meta( $plan_id, 'custom_price_label' );
		delete_post_meta( $plan_id, 'button_text' );
		delete_post_meta( $plan_id, 'button_link' );
		delete_post_meta( $plan_id, 'features' );
		delete_post_meta( $plan_id, 'is_starred' );
		delete_post_meta( $plan_id, 'is_annual' );
		delete_post_meta( $plan_id, 'is_enabled' );
	}
}

$all_plans = get_posts( [
	'post_type'      => 'plan',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'fields'         => 'ids',
] );

if ( $all_plans ) {
	foreach ( $all_plans as $plan_id ) {
		wp_delete_post( $plan_id, true );
	}
}
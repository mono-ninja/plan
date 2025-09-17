<?php

namespace Plan\Core;

use Plan\Plan;

defined( 'ABSPATH' ) || exit;
class PlanCache {

	public function __construct() {
		add_action( 'save_post_plan',        [ $this, 'bust_on_save' ], 10, 3 );
		add_action( 'transition_post_status',[ $this, 'bust_on_status' ], 10, 3 );
		add_action( 'trashed_post',          [ $this, 'bust_on_delete' ] );
		add_action( 'before_delete_post',    [ $this, 'bust_on_delete' ] );
	}

	private function bust(): void {
		delete_transient( Plan::CACHEKEY );
	}

	public function bust_on_save( int $post_id, \WP_Post $post, bool $update ): void {
		if ( $post->post_type !== 'plan' ) return;
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) return;
		$this->bust();
	}

	public function bust_on_status( string $new, string $old, \WP_Post $post ): void {
		if ( $post->post_type !== 'plan' || $new === $old ) return;
		$this->bust();
	}

	public function bust_on_delete( int $post_id ): void {
		if ( get_post_type( $post_id ) !== 'plan' ) return;
		$this->bust();
	}

}

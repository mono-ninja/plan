<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;

abstract class MetaBox {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'register' ] );
		add_action( 'save_post', [ $this, 'maybe_save' ], 10, 2 );
	}

	abstract public function id(): string;

	abstract public function title(): string;

	abstract public function screen(): string;

	abstract public function context(): string;

	abstract public function priority(): string;

	public function nonce_action(): string {
		return 'save_' . $this->id();
	}

	public function nonce_name(): string {
		return $this->id() . '_nonce';
	}

	public function register(): void {
		add_meta_box(
			$this->id(),
			$this->title(),
			[ $this, 'render_with_nonce' ],
			$this->screen(),
			$this->context(),
			$this->priority()
		);
	}

	public function render_with_nonce( $post ): void {
		wp_nonce_field( $this->nonce_action(), $this->nonce_name() );
		$this->render( $post );
	}

	public function maybe_save( int $post_id, \WP_Post $post ): void {
		if ( $post->post_type !== $this->screen() ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options', $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST[ $this->nonce_name() ] ) ||
		     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_name() ] ) ), $this->nonce_action() ) ) {
			return;
		}

		$this->save( $post_id );
	}

	abstract public function render( $post ): void;

	abstract public function save( int $post_id ): void;
}
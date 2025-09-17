<?php

namespace Plan\CPT;

class Status extends MetaBox {
	public function id(): string {
		return 'plan_status';
	}

	public function title(): string {
		return __( 'Plan Status', 'plan' );
	}

	public function screen(): string {
		return 'plan';
	}

	public function context(): string {
		return 'side';
	}

	public function priority(): string {
		return 'high';
	}

	public function render( $post ): void {
		$is_annual  = (bool) get_post_meta( $post->ID, 'is_annual', true );
		$is_starred = (bool) get_post_meta( $post->ID, 'is_starred', true );
		$is_enabled = (bool) get_post_meta( $post->ID, 'is_enabled', true );
		?>
        <p>
            <label>
                <input type="checkbox" name="is_annual" value="1" <?php checked( $is_annual ); ?>>
				<?php esc_html_e( 'Annual', 'plan' ); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_starred" value="1" <?php checked( $is_starred ); ?>>
				<?php esc_html_e( 'Recommended', 'plan' ); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_enabled" value="1" <?php checked( $is_enabled ); ?>>
				<?php esc_html_e( 'Enabled', 'plan' ); ?>
            </label>
        </p>
		<?php
	}

	public function save( int $post_id ): void {
		update_post_meta( $post_id, 'is_annual', isset( $_POST['is_annual'] ) ? 1 : 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $post_id, 'is_starred', isset( $_POST['is_starred'] ) ? 1 : 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $post_id, 'is_enabled', isset( $_POST['is_enabled'] ) ? 1 : 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}
}

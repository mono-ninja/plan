<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;

class Features extends MetaBox {
	public function id(): string {
		return 'plan_features';
	}

	public function title(): string {
		return __( 'Plan Features', 'plan' );
	}

	public function screen(): string {
		return 'plan';
	}

	public function context(): string {
		return 'normal';
	}

	public function priority(): string {
		return 'default';
	}

	public function render( $post ): void {
		$features = (array) get_post_meta( $post->ID, 'features', true );
		if ( empty( $features ) ) {
			$features = [ '' ];
		}
		?>
        <ul id="plan-features-list" class="plan-features-list">
			<?php foreach ( $features as $feature ) : ?>
                <li class="feature-item" draggable="true">
                    <span class="handle"><span class="dashicons dashicons-move" aria-hidden="true"></span></span>
                    <input type="text" class="input-feature" name="features[]"
                           value="<?php echo esc_attr( $feature ); ?>">
                    <button type="button" class="button remove-feature">-</button>
                </li>
			<?php endforeach; ?>
        </ul>
        <p>
            <button type="button" id="add-feature" class="button button-primary">
                + <?php esc_html_e( 'Add Feature', 'plan' ); ?></button>
        </p>
		<?php
	}

	public function save( int $post_id ): void {
		if ( isset( $_POST['features'] ) && is_array( wp_unslash( $_POST['features'] ) ) ) {
			$features = array_filter( array_map( 'sanitize_text_field', wp_unslash($_POST['features'] )) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_post_meta( $post_id, 'features', $features );
		} else {
			delete_post_meta( $post_id, 'features' );
		}
	}
}
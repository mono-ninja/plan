<?php

namespace Plan\CPT;

defined( 'ABSPATH' ) || exit;

class General extends MetaBox {
	public function id(): string {
		return 'plan_general';
	}

	public function title(): string {
		return __( 'Plan Details', 'plan' );
	}

	public function screen(): string {
		return 'plan';
	}

	public function context(): string {
		return 'normal';
	}

	public function priority(): string {
		return 'high';
	}

	public function render( $post ): void {
		$price    = get_post_meta( $post->ID, 'price', true );
		$label    = get_post_meta( $post->ID, 'custom_price_label', true );
		$btn_text = get_post_meta( $post->ID, 'button_text', true );
		$btn_link = get_post_meta( $post->ID, 'button_link', true );
		?>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><label for="price"><?php esc_html_e( 'Price', 'plan' ); ?></label></th>
                <td><input id="price" type="number" step="0.01" min="0" name="price"
                           value="<?php echo esc_attr( $price ); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="custom-price-label"><?php esc_html_e( 'Custom Price Label', 'plan' ); ?></label></th>
                <td>
                    <input id="custom-price-label" type="text" name="custom_price_label"
                           value="<?php echo esc_attr( $label ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button-text"><?php esc_html_e( 'Button Text', 'plan' ); ?></label></th>
                <td><input id="button-text" type="text" name="button_text" value="<?php echo esc_attr( $btn_text ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button-link"><?php esc_html_e( 'Button Link', 'plan' ); ?></label></th>
                <td><input id="button-link" type="url" name="button_link" value="<?php echo esc_url( $btn_link ); ?>">
                </td>
            </tr>
        </table>
		<?php
	}

	public function save( int $post_id ): void {
		update_post_meta( $post_id, 'price', sanitize_text_field( wp_unslash( $_POST['price'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $post_id, 'custom_price_label', sanitize_text_field( wp_unslash( $_POST['custom_price_label'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $post_id, 'button_text', sanitize_text_field( wp_unslash( $_POST['button_text'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $post_id, 'button_link', esc_url_raw( wp_unslash( $_POST['button_link'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}
}
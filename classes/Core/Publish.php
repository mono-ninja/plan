<?php

namespace Plan\Core;

defined( 'ABSPATH' ) || exit;

use Plan\Plan;

class Publish {

	public function __construct() {
		add_shortcode( Plan::SHORTCODE, [ $this, 'shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function shortcode() {

		$cache_key = Plan::CACHEKEY;
		$query     = get_transient( $cache_key );

		if ( false === $query ) {
			$query = new \WP_Query( [
				'post_type'      => 'plan',
				'posts_per_page' => - 1,
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
				'meta_query'     => [
					[
						'key'   => 'is_enabled',
						'value' => 1,
					],
				],
			] );

			set_transient( $cache_key, $query, Plan::CACHE_TTL );
		}

		if ( ! $query->have_posts() ) {
			return '<p>' . esc_html__( 'No plans available.', 'plan' ) . '</p>';
		}

		ob_start();
		echo '<section class="plans-wrapper">';
		echo '<div class="screen-reader-text">' . esc_html__( 'Price Plans', 'plan' ) . '</div>';
        echo '<div class="plans-switcher-box"><div class="plans-switcher">';
        echo '<label class="switch"><input type="checkbox" id="plan-toggle"><span class="slider"></span></label>
	            <span class="label">'.esc_html__('Show annual plans', 'plan').'</span>';
        echo '</div></div>';
		echo '<div class="plans-box">';
		while ( $query->have_posts() ) {
			$query->the_post();

			$price     = get_post_meta( get_the_ID(), 'price', true );
			$label     = get_post_meta( get_the_ID(), 'custom_price_label', true );
			$btn_text  = get_post_meta( get_the_ID(), 'button_text', true );
			$btn_link  = get_post_meta( get_the_ID(), 'button_link', true );
			$features  = (array) get_post_meta( get_the_ID(), 'features', true );
			$is_star   = (bool) get_post_meta( get_the_ID(), 'is_starred', true );
			$is_annual = (bool) get_post_meta( get_the_ID(), 'is_annual', true );

			$classes   = [ 'plan-item' ];
			$classes[] = $is_annual ? 'is-annual' : 'is-monthly';
			if ( $is_star ) {
				$classes[] = 'is-starred';
			}
			?>
            <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
                <h3 class="plan-title"><?php the_title(); ?></h3>

				<?php if ( $is_star ) : ?>
                    <div class="plan-badge"><?php esc_html_e( 'Recommended', 'plan' ); ?></div>
				<?php endif; ?>

                <div class="plan-price">
                    <?php if ( empty( $label ) ) : ?>
                        <span class="plan-price-currency">$</span>
                        <div class="plan-price-amount"><?php echo esc_html( $price ); ?>
                            <span class="plan-period">
                            / <?php echo $is_annual ? esc_html__( 'annually', 'plan' ) : esc_html__( 'a month', 'plan' ); ?>
                        </span>
                        </div>
                    <?php else: ?>
					    <span class="plan-price-text"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                </div>

	            <?php if ( $btn_text && $btn_link ) : ?>
                <div class="plan-item-button">
                    <a class="plan-button" href="<?php echo esc_url( $btn_link ); ?>">
				            <?php echo esc_html( $btn_text ); ?>
                    </a>
                </div>
	            <?php endif; ?>

				<?php if ( ! empty( $features ) ) : ?>
                    <ul class="plan-features">
						<?php foreach ( $features as $feature ) : ?>
                            <li><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>


            </div>
			<?php
		}
		echo '</div>';
		echo '</section>';
		wp_reset_postdata();

		return ob_get_clean();

	}

	public function enqueue_scripts(): void {
		wp_enqueue_style( 'plan', Plan::url() . 'assets/css/front.css', [], Plan::info( 'version' ) );
        wp_enqueue_script( 'plan', Plan::url() . 'assets/js/front.js', [], Plan::info('version'), true);
	}
}
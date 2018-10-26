<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class('prod-review'); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>">

		<?php if ( $comment->comment_approved == '0' ) : ?>

			<h3 class="prod-review-notapproved"><?php esc_html_e( 'Your comment is awaiting approval', 'motor' ); ?></h3>

		<?php else : ?>

			<?php
			if (!empty($comment->user_id)) {
				$user_author_meta = get_user_meta($comment->user_id);
			}
			?>
			<h3 itemprop="author"><?php
				if (!empty($user_author_meta['first_name'][0]) || !empty($user_author_meta['last_name'][0])) {
					if (!empty($user_author_meta['first_name'][0])) {
						echo esc_attr($user_author_meta['first_name'][0]).' ';
					}
					if (!empty($user_author_meta['last_name'][0])) {
						echo esc_attr($user_author_meta['last_name'][0]);
					}
				} else {
					comment_author();
				}
				?></h3>

			<?php

			if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
				if ( $verified )
					echo '<em class="verified">(' . esc_html__( 'verified owner', 'motor' ) . ')</em> ';

			?>

		<?php endif; ?>
		<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) : ?>

			<p itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="prod-review-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'motor' ), $rating ) ?>">
				<?php for ($i = 1; $i <= $rating; $i++) { ?>
					<i class="fa fa-star"></i>
				<?php } ?>
				<?php for ($i = 1; $i <= (5 - $rating); $i++) { ?>
					<i class="fa fa-star-o"></i>
				<?php } ?>
				<time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></time>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>

		<?php do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

		<?php comment_text(); ?>

		<?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>

	</div>


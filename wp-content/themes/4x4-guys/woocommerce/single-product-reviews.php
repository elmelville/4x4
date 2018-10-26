<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>


<div id="reviews">

<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

	<div id="review_form_wrapper" class="prod-addreview-form">
		<div id="review_form">
			<?php
			$commenter = wp_get_current_commenter();

			$comment_form = array(
				'title_reply'          => have_comments() ? esc_html__( 'Add a Review', 'motor' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'motor' ), get_the_title() ),
				'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'motor' ),
				'title_reply_before'   => '<p class="prod-tab-addreview" id="reply-title">',
				'title_reply_after'    => '</p>',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'fields'               => array(
					'author' => '<input placeholder="'.esc_html__( 'Name', 'motor' ).'" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />',
					'email'  => '<input placeholder="'.esc_html__( 'E-mail', 'motor' ).'" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />',
				),
				'submit_field'         => '%1$s %2$s',
				'label_submit'  => esc_html__( 'Add a Review', 'motor' ),
				'logged_in_as'  => '',
				'comment_field' => ''
			);

			if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
				$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'woocommerce' ), esc_url( $account_page_url ) ) . '</p>';
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
				$comment_form['comment_field'] = '<div class="comment-form-rating"><select name="rating" id="rating">
					<option value="">' . esc_html__( 'Rate&hellip;', 'motor' ) . '</option>
					<option value="5">' . esc_html__( 'Perfect', 'motor' ) . '</option>
					<option value="4">' . esc_html__( 'Good', 'motor' ) . '</option>
					<option value="3">' . esc_html__( 'Average', 'motor' ) . '</option>
					<option value="2">' . esc_html__( 'Not that bad', 'motor' ) . '</option>
					<option value="1">' . esc_html__( 'Very Poor', 'motor' ) . '</option>
				</select></div>';
			}

			$comment_form['comment_field'] .= '<textarea placeholder="'.esc_html__( 'Message', 'motor' ).'" id="comment" name="comment" aria-required="true"></textarea>';

			comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
			?>
		</div>
	</div>

<?php else : ?>

	<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'motor' ); ?></p>

<?php endif; ?>



<ul id="comments">

<?php if ( have_comments() ) : ?>

	<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
	
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'type'      => 'list',
		) ) );
	endif; ?>

<?php else : ?>

	<li class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'motor' ); ?></li>

<?php endif; ?>

</ul>

</div>

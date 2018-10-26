<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package motor
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="post-comments<?php if ( get_comments_number() == 0 ) : ?> post-nocomments<?php endif; ?>">

	<div class="post-addcomment-form" id="post-addcomment-form"<?php if (isset($_GET['replytocom'])) echo ' style="display: block;"'; ?>>
	<?php
	$commenter = wp_get_current_commenter();
	$html_req = ( $req ? " required='required'" : '' );
	$html5    = 'html5';
	comment_form( array(
		'title_reply_before' => '<p class="post-addcomment-ttl" id="reply-title">',
		'title_reply_after'  => '</p>',
		'fields' => array(
			'author' => '<input placeholder="'.esc_html__('Name', 'motor').'" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" maxlength="245"' . $html_req . ' />',
			'email'  => '<input placeholder="'.esc_html__('E-mail', 'motor').'" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" maxlength="100" aria-describedby="email-notes"' . $html_req  . ' />',
			'url' => '',
		),
		'comment_field'        => '<textarea placeholder="'.esc_html__('Comment', 'motor').'" id="comment" name="comment" maxlength="65525" required="required"></textarea>',
		'comment_notes_before' => '',
	) );
	?>
	</div>

	<?php if ( comments_open() ) { ?>
		<a href="#" class="post-comments-add" id="post-comments-add"><?php echo esc_html__('Add a comment', 'motor'); ?></a>
	<?php } ?>
	

	<?php if ( have_comments() ) : ?>
		<h3 class="post-comments-ttl">
			<?php
				$comments_number = get_comments_number();
				if ( 1 === $comments_number ) {
					/* translators: %s: post title */
					printf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'motor' ), get_the_title() );
				} else {
					printf(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'%1$s thought on &ldquo;%2$s&rdquo;',
							'%1$s thoughts on &ldquo;%2$s&rdquo;',
							$comments_number,
							'comments title',
							'motor'
						),
						number_format_i18n( $comments_number ),
						get_the_title()
					);
				}
			?>
		</h3>

		<ul>
			<?php
			wp_list_comments( array(
				'style'       => 'ul',
				'short_ping'  => true,
				'avatar_size' => 80,
				'callback'    => 'motor_comment',
			) );
			?>
		</ul><!-- .comment-list -->

		<?php //the_comments_navigation(array('screen_reader_text'=>' ')); ?>
		<?php paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'type'      => 'list',
		) ) ); ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'motor' ); ?></p>
	<?php endif; ?>

</div><!-- .comments-area -->

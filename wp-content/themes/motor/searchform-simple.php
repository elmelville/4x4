<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-search">
    <input value="<?php echo get_search_query(); ?>" name="s" type="text" placeholder="<?php echo esc_html__('Search for products...', 'motor'); ?>">
    <button type="submit"><i class="fa fa-search"></i></button>
</form>
<?php

// Main Font
// fonts_main, fonts_main_fw, fonts_main_fs, fonts_main_size, fonts_main_lh, fonts_main_ls, fonts_main_align, fonts_main_color, fonts_main_tt
motor_set_font_variables(array(
	'type' => 'fonts_main',
	'font-family' => 'Open Sans',
	'font-weight' => '400',
	'font-size' => '15px',
	'line-height' => '1.8',
	'letter-spacing' => '0px',
	'text-align' => 'left',
	'color' => '#868ca7',
	'text-transform' => 'none',
	'font-style' => 'initial',
));

// Main Title Font
// fonts_main_ttl, fonts_main_ttl_fw, fonts_main_ttl_fs, fonts_main_ttl_size, fonts_main_ttl_lh, fonts_main_ttl_ls, fonts_main_ttl_align, fonts_main_ttl_color, fonts_main_ttl_tt
motor_set_font_variables(array(
	'type' => 'fonts_main_ttl',
	'font-family' => 'Montserrat',
	'font-weight' => '900',
	'font-size' => '30px',
	'line-height' => '1.1',
	'letter-spacing' => '0.05em',
	'text-align' => 'left',
	'color' => '#283346',
	'text-transform' => 'uppercase',
	'font-style' => 'initial',
));

// Normal Title Font
// fonts_normal_ttl, fonts_normal_ttl_fw, fonts_normal_ttl_fs, fonts_normal_ttl_size, fonts_normal_ttl_lh, fonts_normal_ttl_ls, fonts_normal_ttl_align, fonts_normal_ttl_color, fonts_normal_ttl_tt
motor_set_font_variables(array(
	'type' => 'fonts_normal_ttl',
	'font-family' => 'Montserrat',
	'font-weight' => '700',
	'font-size' => '30px',
	'line-height' => '1.1',
	'letter-spacing' => '0px',
	'text-align' => 'left',
	'color' => '#283346',
	'text-transform' => 'none',
	'font-style' => 'initial',
));




// Primary Color
$color_primary = get_theme_mod('color_primary', '#ff3100');
add_less_var( 'color_primary', $color_primary );

// Body Background
$color_body = get_theme_mod('color_body', '#f4f5fb');
add_less_var( 'color_body', $color_body );

// Header Background Color
$color_header = get_theme_mod('color_header', '#18202e');
add_less_var( 'color_header', $color_header );

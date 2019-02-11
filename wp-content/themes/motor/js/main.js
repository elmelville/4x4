"use strict";

function motor_initslider(refresh, parent) {

	var slider_thumbs = '#prod-thumbs';
	var slider_main = '#prod-slider';

	if (refresh) {
		jQuery('#prod-thumbs').removeData("flexslider");
		jQuery('#prod-thumbs .slides').find("li").off();
		jQuery('#prod-slider').removeData("flexslider");
		var slides_html = jQuery('#prod-slider .slides').html();
		jQuery('#prod-slider .slides').html(slides_html);
	}

	if (parent) {
		slider_thumbs = parent + ' ' + slider_thumbs;
		slider_main = parent + ' ' + slider_main;
	}

	jQuery(slider_thumbs).flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 97,
		itemMargin: 0,
		minItems: 4,
		maxItems: 4,
		asNavFor: slider_main,
		start: function(slider){
			jQuery(slider_thumbs).resize();
		}
	});
	jQuery(slider_main).flexslider({
		animation: "fade",
		animationSpeed: 500,
		slideshow: false,
		animationLoop: false,
		smoothHeight: false,
		controlNav: false,
		sync: slider_thumbs,
	});
}
function motor_get_cookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}
function motor_in_array(needle, haystack, strict) {
	var found = false, key, strict = !!strict;
	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}
	return found;
}

function getUpdatedGiftCardTotal (qnt) {
	const input = jQuery('.prod-qnt-wrap .qnt-wrap input, .prod-li-qnt-wrap .qnt-wrap input');
	const pricePerCard = parseFloat(jQuery('#gift-card-single-price.prod-price-wrap .prod-price .woocommerce-Price-amount.amount, .gift_card .prod-li-cont .prod-li-price-wrap .prod-li-price .woocommerce-Price-amount.amount').text().slice(1));
	const priceTotal = number_format(qnt*pricePerCard, input.data('decimals'), input.data('decimal_separator'), input.data('thousand_separator'));

	return priceTotal;
}

// WooCommerce Ajax Cart
function motor_ajax_cart(element) {
    var form = element.closest('form');
    var total_items = 0;

	// emulates button Update cart click
    jQuery("<input type='hidden' name='update_cart' id='update_cart' value='1'>").appendTo(form);

	// plugin flag
    jQuery("<input type='hidden' name='is_wac_ajax' id='is_wac_ajax' value='1'>").appendTo(form);

    var el_qty = element;
    var matches = element.attr('name').match(/cart\[(\w+)\]/);
    var cart_item_key = matches[1];
    form.append( jQuery("<input type='hidden' name='cart_item_key' id='cart_item_key'>").val(cart_item_key) );

	// when qty is set to zero, then fires default woocommerce remove link
    if ( el_qty.val() == 0 ) {
        var removeLink = element.closest('.cart_item').find('.product-remove a');
        removeLink.click();

        return false;
    }

	// get the form data before disable button...
    var formData = form.serialize();

    jQuery("a.checkout-button.wc-forward").addClass('disabled');

    jQuery.post( form.attr('action'), formData, function(resp) {
    	jQuery('.cart-subtotal').html(jQuery(resp).find('.cart-subtotal').html());
    	jQuery('.order-total').html(jQuery(resp).find('.order-total').html());

        jQuery('#update_cart').remove();
        jQuery('#is_wac_ajax').remove();
        jQuery('#cart_item_key').remove();

        jQuery("a.checkout-button.wc-forward").removeClass('disabled');

		// fix to update "Your order" totals when cart is inside Checkout page
        if ( jQuery( '.woocommerce-checkout' ).length ) {
            jQuery( document.body ).trigger( 'update_checkout' );
        }

        jQuery( document.body ).trigger( 'updated_cart_totals' );

		jQuery('.header-cart-summ').html(jQuery(resp).find('.order-total .woocommerce-Price-amount').html());
		jQuery('.input-text.qty').each(function(){
			total_items += parseInt(jQuery(this).val());
		});
		jQuery('.header-cart-count span').text(total_items);        
    });

    return true;
}






function number_format (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? '' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

jQuery(document).ready(function ($) {

	// Modal Form
	$('.callback').fancybox({
		padding: 0,
		content: $('#modal-form'),
		helpers: {
			overlay: {
				locked: false
			}
		},
		tpl: {
			closeBtn: '<a title="Close" class="fancybox-item fancybox-close modal-form-close" href="javascript:;"></a>'
		}
	});

	// Modal Form - Request Product
	$('.request-form-btn').fancybox({
		padding: 0,
		content: $('#request-form'),
		helpers: {
			overlay: {
				locked: false
			}
		},
		tpl: {
			closeBtn: '<a title="Close" class="fancybox-item fancybox-close modal-form-close" href="javascript:;"></a>'
		}
	});


	// Fancybox Images
	$('.fancy-img').fancybox({
		padding: 0,
		margin: [60, 50, 20, 50],
		helpers: {
			overlay: {
				locked: false
			},
			thumbs: {
				width: 60,
				height: 60
			}
		},
		tpl: {
			closeBtn: '<a title="Close" class="fancybox-item fancybox-close modal-form-close2" href="javascript:;"></a>',
			prev: '<a title="Previous" class="fancybox-nav fancybox-prev modal-prev" href="javascript:;"><span></span></a>',
			next: '<a title="Next" class="fancybox-nav fancybox-next modal-next" href="javascript:;"><span></span></a>',
		}
	});

	// Modal Videos
	$(".motor-gallery").on('click', ".motor-gallery-video", function() {
		$.fancybox({
			'padding'       : 0,
			'autoScale'     : false,
			'transitionIn'  : 'none',
			'transitionOut' : 'none',
			'href'          : this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
			'type'          : 'swf',
			'swf'           : {
				'wmode'             : 'transparent',
				'allowfullscreen'   : 'true'
			},
			tpl: {
				closeBtn: '<a title="Close" class="fancybox-item fancybox-close modal-form-close2" href="javascript:;"></a>'
			}
		});
		return false;
	});


	// Dropdown
	if ($('.dropdown-wrap').length > 0) {
		$('.dropdown-wrap').on('click', '.dropdown-title', function () {
			$('.dropdown-list').slideUp(200);
			if ($(this).hasClass('opened')) {
				$(this).removeClass('opened');
			} else {
				$('.dropdown-wrap .dropdown-title').removeClass('opened');
				$(this).addClass('opened');
				$(this).next('.dropdown-list').slideDown(200);
			}
			return false;
		});
		$('.dropdown-wrap .dropdown-list li').on('click', 'a', function () {
			$(this).closest('.dropdown-wrap').find('.dropdown-title').text($(this).text());
			if ($(this).data('value')) {
				var per_page_select = $(this).parents('.products-per-page').find('select');
				var per_page_val = $(this).data('value');
				per_page_select.val(per_page_val).trigger('change');
				$('.dropdown-list').slideUp(200);
				$('.dropdown-wrap .dropdown-title').removeClass('opened');
				return false;
			}
		});
	}

	if ($('.dropdown-wrap').length > 0) {
		$('body').on('click', function () {
			if ($('.dropdown-wrap').length > 0) {
				$('.dropdown-list').slideUp(200);
				$('.dropdown-wrap .dropdown-title').removeClass('opened');
			}
		});
	}

	$('a.menu-item-has-children').on('click',function(e){
		e.preventDefault();
		$('.menu-bg').show();
		$(this).parent().siblings().find('active').removeClass('active');
		$(this).parent().siblings().removeClass('active');
		$(this).parent().toggleClass('active');		
	});

	$('.menu-bg').on('click',function(e){
		$('.menu-bg').hide();
		$('.menu-item-has-children').removeClass('active');
	});

	$('.close-menu').on('click',function(){
		$('.active').removeClass('active');
		$('.menu-bg').hide();
	});
/*

	// Top Menu Seacrh
	$('.header').on('click', '#header-searchbtn', function () {
		if ($(this).hasClass('opened')) {
			$(this).removeClass('opened');
			//$('#header-search').hide();
		} else {
			$(this).addClass('opened');
			//$('#header-search').show();
		}
		return false;
	});


	// Top Menu
	$('.header').on('click', '#header-menutoggle', function () {
		if ($(this).hasClass('opened')) {
			$(this).removeClass('opened');
			$('#top-menu').fadeOut();
		} else {
			$(this).addClass('opened');
			$('#top-menu').fadeIn();
		}
		return false;
	});
	// Top SubMenu
	$('#top-menu').on('click', '.menu-item-has-children > a', function () {
		if ($(window).width() <= 768) {
			if ($(this).hasClass('opened')) {
				$(this).removeClass('opened');
				$(this).next('ul').slideUp();
			} else {
				$(this).addClass('opened');
				$(this).next('ul').slideDown();
			}
			return false;
		}
	});

*/

	// Section Menu
	if ($('#section-menu-btn').length > 0) {
		$('.section-top').on('click', '#section-menu-btn', function () {
			if ($(this).hasClass('opened')) {
				$(this).removeClass('opened').html($(this).data('showtext'));
				$('#section-menu-wrap').fadeOut(200);
				$('.section-menu-overlay').fadeOut(200).remove();
			} else {
				$(this).addClass('opened').width($(this).width()).html($(this).data('hidetext'));
				$('#section-menu-wrap').fadeIn(200);
				$('body').append('<div class="section-menu-overlay"></div>');
				$('.section-menu-overlay').fadeIn(200);

				$('body').on('click', '.section-menu-overlay', function () {
					$('#section-menu-btn').removeClass('opened').html($('#section-menu-btn').data('showtext'));
					$('#section-menu-wrap').fadeOut(200);
					$('.section-menu-overlay').fadeOut(200).remove();
					return false;
				});
			}
			return false;
		});

		$('.section-top #section-menu-wrap > ul > li > ul > li > a').on('click', function () {
			var link = $(this);
			var parent = link.parent();
			var child = parent.find('> ul');
			if (child.length > 0 && !link.hasClass('opened')) {
				$('.section-menu-wrap a').removeClass('opened');
				link.addClass('opened');
				$('.section-menu-wrap > ul > li > ul > li > ul').slideUp();
				child.slideToggle();
				return false;
			}
		});
	}


	// Filter Button
	if ($('#section-filter-toggle-btn').length > 0 && $('#section-filter .woof_redraw_zone').length > 0) {
		$('.section-filter-toggle').on('click', '#section-filter-toggle-btn', function () {
			if ($(this).parent().hasClass('filter_hidden')) {
				$(this).text($(this).data('hidetext')).parent().removeClass('filter_hidden');
				//$('#section-filter .woof_redraw_zone').slideDown();
				document.cookie = "filter_toggle=filter_opened; expires=Thu, 31 Dec 2020 23:59:59 GMT; path=/;";
			} else {
				$(this).text($(this).data('showtext')).parent().addClass('filter_hidden');
				//$('#section-filter .woof_redraw_zone').slideUp();
				document.cookie = "filter_toggle=filter_hidden; expires=Thu, 31 Dec 2020 23:59:59 GMT; path=/;";
			}

			return false;
		});
	}

	// Sticky sidebar
	if ($('#section-sb').length > 0 && $('#section-list-withsb').length > 0) {
		$('#section-sb, #section-list-withsb').theiaStickySidebar({
			additionalMarginTop: 30
		});
	}


	// Product Tabs
	$('body').on('click', '.prod-tabs li a', function () {
		if ($(this).parent().hasClass('prod-tabs-addreview') || $(this).parent().hasClass('active') || $(this).attr('data-prodtab') == '')
			return false;
		$('.prod-tabs li').removeClass('active');
		$(this).parent().addClass('active');

		// mobile
		$('.prod-tab-mob').removeClass('active');
		$('.prod-tab-mob[data-prodtab-num=' + $(this).parent().data('prodtab-num') + ']').addClass('active');

		$('.prod-tab-cont .prod-tab').hide();
		$($(this).attr('data-prodtab')).fadeIn();

		return false;
	});

	// Product Tabs (mobile)
	$('body').on('click', '.prod-tab-cont .prod-tab-mob', function () {
		if ($(this).hasClass('active') || $(this).attr('data-prodtab') == '')
			return false;
		$('.prod-tab-cont .prod-tab-mob').removeClass('active');
		$(this).addClass('active');

		// main
		$('.prod-tabs li').removeClass('active');
		$('.prod-tabs li[data-prodtab-num=' + $(this).data('prodtab-num') + ']').addClass('active');

		$('.prod-tab-cont .prod-tab').slideUp();
		$($(this).attr('data-prodtab')).slideDown();
		return false;
	});

	$('body').on('click', '.prod-tabs-addreview', function () {
		if ($('.prod-tabs li.active').attr('id') == 'prod-reviews') {
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		} else {
			$('.prod-tabs li').removeClass('active');
			$('.prod-tab-cont .prod-tab-mob').removeClass('active');
			$('#prod-reviews').addClass('active');
			$('.prod-tab-mob-reviews').addClass('active');

			$('.prod-tab-cont .prod-tab').hide();
			$('.prod-tab-cont .prod-tab.prod-reviews').fadeIn();
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		}

		$('#review_form_wrapper').fadeIn();

		return false;
	});

	if ($('.prod-tab #commentform #submit').length > 0) {
		var filterEmail  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
		$('body').on('click', '.prod-tab #commentform #submit', function () {
			var errors = false;
			if (!$(this).parents('#commentform').find('#rating').val()) {
				$('.prod-tab-addreview').addClass('error');
				errors = true;
			}
			$(this).parents('#commentform').find('input[type=text], input[type=email], textarea').each(function () {
				if ($(this).attr('id') == 'email'){
					if (!filterEmail.test($(this).val())) {
						$(this).addClass("redborder");
						errors++;
					}
					else {
						$(this).removeClass("redborder");
					}
					return;
				}
				if ($(this).val() == '') {
					$(this).addClass('redborder');
					errors++;
				} else {
					$(this).removeClass('redborder');
				}
			});

			if (errors)
				return false;
		});
	}

	// Show Properties
	$('.prod-cont').on('click', '#prod-showprops', function () {
		if ($('.prod-tabs li.active').attr('id') == 'prod-props') {
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		} else {
			$('.prod-tabs li').removeClass('active');
			$('#prod-props').addClass('active');
			$('.prod-tab-cont .prod-tab').hide();
			$('#prod-tab-2').fadeIn();
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		}
		return false;
	});

	// Show Description
	$('.prod-cont').on('click', '#prod-showdesc', function () {
		if ($('.prod-tabs li.active').attr('id') == 'prod-desc') {
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		} else {
			$('.prod-tabs li').removeClass('active');
			$('#prod-desc').addClass('active');
			$('.prod-tab-cont .prod-tab').hide();
			$('#prod-tab-1').fadeIn();
			$('html, body').animate({scrollTop: ($('.prod-tabs-wrap').offset().top - 10)}, 700);
		}
		return false;
	});



	// Post Add Comment Form
	$('.post-comments').on('click', '#post-comments-add', function () {
		$('#post-addcomment-form').slideDown();
		return false;
	});


	// Events
	if ($("#blog-calendar").length > 0) {
		if (typeof eventData !== "undefined") {
			$("#blog-calendar").zabuto_calendar({
				action: function () {
					return myDateFunction(this.id);
				},
				today: true,
				nav_icon: {
					prev: '<i class="fa fa-caret-left"></i>',
					next: '<i class="fa fa-caret-right"></i>'
				},
				data: eventData,
			});
		}
	}


	// Select Styler
	if ($('.blog-sb-widget:not(.WOOF_Widget) select').length > 0) {
		$('.blog-sb-widget:not(.WOOF_Widget) select').chosen({
			disable_search_threshold: 10
		});
	}
	if ($('.shipping-calculator-form select').length > 0) {
		$('.shipping-calculator-form select').chosen({
			disable_search_threshold: 10
		});
	}


	// AJAX pagination scroll
	$('body.archive.woocommerce').on('click', 'ul.page-numbers a.page-numbers', function () {
		if ($('#woof_results_by_ajax').length > 0) {
			$('html, body').animate({scrollTop: $('#woof_results_by_ajax').offset().top - 30}, 800);
		}
	});


	// Product List Show Information
	$('body').on('click', '.prod-li-infobtn', function () {
		var product_info = $(this).parents('.prod-li').find('.prod-li-informations');
		$(this).toggleClass('opened');
		if (product_info.length) {
			product_info.slideToggle();
		}
		return false;
	});






	// Update Compare Count & Active Classes
	var compare_list = motor_get_cookie("compare-list");
	if (typeof compare_list !== 'undefined' && compare_list != '') {
		var compare_list_arr = compare_list.split(':');
		compare_list_arr.pop();

		if (compare_list_arr.length > 0 && $('.header-compare').length > 0) {
			if ($('#h-compare-count').length > 0) {
				$('#h-compare-count').text(compare_list_arr.length);
			} else {
				$('.header-compare').append('<span id="h-compare-count">' + compare_list_arr.length + '</span>');
			}
		} else if (compare_list_arr.length === 0 && $('#h-compare-count').length > 0) {
			$('#h-compare-count').text(compare_list_arr.length);
		}
		if ($('#h-personal-compare-count').length > 0) {
			$('#h-personal-compare-count').text(compare_list_arr.length);
		}

		$('.prod-li-compare-btn').each(function () {
			if ($(this).hasClass('prod-li-compare-added') && !motor_in_array($(this).data('id'), compare_list_arr)) {
				$(this).removeClass('prod-li-compare-added').attr('href', $(this).data('url'));
				$(this).text($(this).data('text'));
			} else if (!$(this).hasClass('prod-li-compare-added') && motor_in_array($(this).data('id'), compare_list_arr)) {
				$(this).addClass('prod-li-compare-added').attr('href', $(this).data('url'));
				$(this).text($(this).data('text'));
			}
		});
	}

	// Update WishList Count & Active Classes
	var wishlist_list = motor_get_cookie("yith_wcwl_products");
	if (typeof wishlist_list !== 'undefined' && wishlist_list != '') {
		var wishlist_list_obj = $.parseJSON(wishlist_list);
		var wishlist_list_ids = [];

		for(var wishlist_item in wishlist_list_obj) {
			wishlist_list_ids[wishlist_list_ids.length] = wishlist_list_obj[wishlist_item]['prod_id'];
		}

		if (wishlist_list_ids.length > 0 && $('.header-favorites').length > 0) {
			if ($('#h-wishlist-count').length > 0) {
				$('#h-wishlist-count').text(wishlist_list_ids.length);
			} else {
				$('.header-favorites').append('<span id="h-wishlist-count">' + wishlist_list_ids.length + '</span>');
			}
		} else if (wishlist_list_ids.length === 0 && $('#h-wishlist-count').length > 0) {
			$('#h-wishlist-count').text(wishlist_list_ids.length);
		}
		if ($('#h-personal-wishlist-count').length > 0) {
			$('#h-personal-wishlist-count').text(wishlist_list_ids.length);
		}

		$('.add_to_wishlist').each(function () {
			var prod_id = $(this).data('product-id');
			var el_wrap = $( '.add-to-wishlist-' + prod_id );
			if (el_wrap.hasClass('wishlist-added') && !motor_in_array(prod_id, wishlist_list_ids)) {
				el_wrap.removeClass('wishlist-added');
				el_wrap.find( '.yith-wcwl-add-button' ).show().removeClass('hide').addClass('show');
				el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).hide().removeClass('show').addClass('hide');
				el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).hide().removeClass('show').addClass('hide');
			} else if (!el_wrap.hasClass('wishlist-added') && motor_in_array(prod_id, wishlist_list_ids)) {
				el_wrap.addClass('wishlist-added');
				el_wrap.find('.yith-wcwl-add-button').hide().removeClass('show').addClass('hide');
				el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).show().removeClass('hide').addClass('show');
			}
		});
	}


	// Updating Wishlist Count
	$('body').on('click', '.add_to_wishlist', function () {
		if ($('.header-favorites').length > 0) {
			var favorites_list = $('.header-favorites #h-wishlist-count').text();
			favorites_list++;
			if ($('#h-wishlist-count').length > 0) {
				$('#h-wishlist-count').text(favorites_list);
			} else {
				$('.header-favorites').append('<span id="h-wishlist-count">' + wishlist_list_ids.length + '</span>');
			}
		}
		if ($('#h-personal-wishlist-count').length > 0) {
			var favorites_list_personal = $('#h-personal-wishlist-count').text();
			favorites_list_personal++;
			$('#h-personal-wishlist-count').text(favorites_list_personal);
		}
	});



	// Filter Toggle
	jQuery('body').on('click', '.woof_front_toggle', function () {
		if (jQuery(this).data('condition') == 'opened') {
			jQuery(this).removeClass('woof_front_toggle_opened');
			jQuery(this).addClass('woof_front_toggle_closed');
			jQuery(this).data('condition', 'closed');
			if (woof_toggle_type == 'text') {
				jQuery(this).text(woof_toggle_closed_text);
			} else {
				jQuery(this).find('img').prop('src', woof_toggle_closed_image);
			}
		} else {
			jQuery(this).addClass('woof_front_toggle_opened');
			jQuery(this).removeClass('woof_front_toggle_closed');
			jQuery(this).data('condition', 'opened');
			if (woof_toggle_type == 'text') {
				jQuery(this).text(woof_toggle_opened_text);
			} else {
				jQuery(this).find('img').prop('src', woof_toggle_opened_image);
			}
		}

		//jQuery(this).parents('.woof_container_inner').find('.woof_block_html_items').toggle(500);
		jQuery(this).parents('.woof_container_inner').find('.woof_block_html_items').toggleClass('woof_closed_block');
		return false;
	});



});



(function($) {
	jQuery(window).load(function(){

		var is_gift_card = !!($('.gift-cards_form.cart').length) || !!($('article.gift_card').length)
		if (is_gift_card) {
			const qtyInput = $('.prod-qnt-wrap .qnt-wrap input, .prod-li-qnt-wrap .qnt-wrap input');
			const priceTotal = getUpdatedGiftCardTotal(qtyInput.val());
			const formattedTotal = qtyInput.data('price_format').replace('%2$s', priceTotal).replace('%1$s', qtyInput.data('currency'));

			$('#gift-card-quantity .prod-price .woocommerce-Price-amount.amount, .gift_card .prod-li-cont .prod-li-total-wrap .prod-li-total .woocommerce-Price-amount.amount').text(formattedTotal);
		}

		// Product Slider
		if ($('#prod-slider').length > 0) {
			motor_initslider(false, '');
		}

		// Variation Image
		if ($('.prod-info .variations select').length > 0) {
			$('.prod-info .variations select').chosen({
				disable_search_threshold: 10
			});
		}
		if ($('.prod-info2 select').length > 0) {
			$('.prod-info2 select').chosen({
				disable_search_threshold: 10
			});
		}
		$( ".variations_form" ).on( "update_variation_values", function () {
			if ($('.prod-info .variations select').length > 0) {
				$('.prod-info .variations select').trigger("chosen:updated");
			}
			if ($('.prod-info2 select').length > 0) {
				$('.prod-info2 select').trigger("chosen:updated");
			}
		} );
		$( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
			if ($('.prod-info .variations select').length > 0) {
				$('.prod-info .variations select').trigger("chosen:updated");
			}
			if ($('.prod-info2 select').length > 0) {
				$('.prod-info2 select').trigger("chosen:updated");
			}
			if ($('.prod-varimg').length) {
				$('.prod-varimg').parents('li').remove();
				motor_initslider(true, '');
			}


			if ($('.variations_form .prod-info .prod-price').length > 0 && $('.variations_form .prod-info .prod-price').hasClass('hidden')) {
				$('.variations_form .prod-info .prod-price').removeClass('hidden');
				$('.woocommerce-variation.single_variation').hide();
			}
		} );
		$( ".variations_form" ).on( "found_variation", function ( event, variation ) {
			var img_exists = false;
			if ($('#prod-slider .slides img[src="' + variation.image.src + '"]').length > 0) {
				img_exists = true;
			}

			if ($('.variations_form .prod-info .prod-price').length > 0 && !$('.variations_form .prod-info .prod-price').hasClass('hidden')) {
				$('.variations_form .prod-info .prod-price').addClass('hidden');
			}
			
			if (variation.image_id && !img_exists) {
				if ($('#prod-slider').length) {
					$('#prod-slider .slides').prepend('<li><a data-fancybox-group="prod" class="fancy-img" href="' + variation.image.src + '"><img class="prod-varimg" src="' + variation.image.src + '" alt=""></a></li>');
					$('#prod-thumbs .slides').prepend('<li><img class="prod-varimg" src="' + variation.image.src + '" alt=""></li>');
					motor_initslider(true);
				} else {
					$('.prod-slider-wrap').prepend('<div class="flexslider prod-slider prod-thumb-only" id="prod-slider"><ul class="slides"><li><a data-fancybox-group="prod" class="fancy-img" href="' + variation.image.src + '"><img class="prod-varimg" src="' + variation.image.src + '" alt=""></a></li></ul></div>');
					motor_initslider(true);
				}
			}
		} );


		if ($('.variations_form .prod-info .prod-price').length > 0) {
			$( ".variations_form" ).trigger( 'woocommerce_variation_select_change' );
			$( ".variations_form" ).trigger( 'check_variations' );
		}



		// Slider "About Us"
		if ($('.content_carousel').length > 0) {
			$('.content_carousel').each(function () {
				if ($(this).data('slideshow_speed') != '') {
					var slideshow_speed =  $(this).data('slideshow_speed');
				} else {
					var slideshow_speed =  '7000';
				}
				if ($(this).data('animation_speed') != '') {
					var animation_speed =  $(this).data('animation_speed');
				} else {
					var animation_speed =  '600';
				}
				if ($(this).data('navigation') == true) {
					var navigation =  true;
				} else {
					var navigation =  false;
				}
				if ($(this).data('pagination') == true) {
					var pagination =  true;
				} else {
					var pagination =  false;
				}
				if ($(this).data('stop_on_hover') == true) {
					var stop_on_hover =  true;
				} else {
					var stop_on_hover =  false;
				}
				$('.content_carousel').flexslider({
					pauseOnHover: stop_on_hover,
					animationSpeed: animation_speed,
					slideshowSpeed: slideshow_speed,
					useCSS: false,
					directionNav: navigation,
					controlNav: pagination,
					animation: "fade",
					slideshow: false,
					animationLoop: true,
					smoothHeight: true
				});
			});
		}


		// Blog sliders
		if ($('.blog-slider').length > 0) {
			$('.blog-slider').flexslider({
				animation: "fade",
				animationSpeed: 500,
				slideshow: false,
				animationLoop: false,
				directionNav: false,
				smoothHeight: false,
				controlNav: true,
			});
		}
		if ($('.post-slider').length > 0) {
			$('.post-slider').flexslider({
				animation: "fade",
				animationSpeed: 500,
				slideshow: false,
				animationLoop: false,
				directionNav: false,
				smoothHeight: true,
				controlNav: true,
			});
		}

		// Slider "Testimonials"
		if ($('.testimonials-car').length > 0) {
			$('.testimonials-car').each(function () {
				var testimonials_slider;
				if ($(this).data('slideshow_speed') != '') {
					var slideshow_speed =  $(this).data('slideshow_speed');
				} else {
					var slideshow_speed =  '7000';
				}
				if ($(this).data('animation_speed') != '') {
					var animation_speed =  $(this).data('animation_speed');
				} else {
					var animation_speed =  '600';
				}
				if ($(this).data('navigation') == true) {
					var navigation =  true;
				} else {
					var navigation =  false;
				}
				if ($(this).data('pagination') == true) {
					var pagination =  true;
				} else {
					var pagination =  false;
				}
				if ($(this).data('stop_on_hover') == true) {
					var stop_on_hover =  true;
				} else {
					var stop_on_hover =  false;
				}
				if ($(this).hasClass('style-1')) {
					var items =  1;
					var item_margin =  0;
				} else {
					var items =  2;
					if ($(window).width() < 751) {
						items =  1;
					}
					var item_margin =  68;
				}
				$(this).flexslider({
					pauseOnHover: stop_on_hover,
					animationLoop: true,
					animation: 'slide',
					animationSpeed: animation_speed,
					slideshowSpeed: slideshow_speed,
					useCSS: false,
					directionNav: navigation,
					controlNav: pagination,
					slideshow: false,
					itemMargin: item_margin,
					itemWidth: 2000,
					maxItems: items,
					minItems: items,
					start: function(slider){
						testimonials_slider = slider;
					}
				});
				$(window).resize(function() {
					if ($(window).width() < 751) {
						testimonials_slider.vars.minItems = 1;
						testimonials_slider.vars.maxItems = 1;
					} else {
						testimonials_slider.vars.minItems = items;
						testimonials_slider.vars.maxItems = items;
					}
				});
			});
		}



		// Quantity
		$('body').on('click change', '.qnt-wrap a, .qnt-wrap input[type=text]', function (e) {
			if (e.type == 'click' && $(this).is('input')) {
				return false;
			}
			var qnt = $(this).parent().find('input').val();
			if ($(this).hasClass('qnt-plus')) {
				qnt++;
			} else if ($(this).hasClass('qnt-minus')) {
				qnt--;
			}
			if (qnt > 0) {
				$(this).parent().find('input').attr('value', qnt);
				if ($(this).parents('.sectls').find('.button').length > 0) {
					$(this).parents('.sectls').find('.button').attr('data-quantity', qnt);
				}
				if ($(this).parents('.prod-cont').find('.button').length > 0) {
					$(this).parents('.prod-cont').find('.button').attr('data-quantity', qnt);
				}
			}

			if (qnt > 0) {

				// Change total price html
				var input = $(this).parent().find('input');
				var prod_price = input.attr('data-qnt-price');
				var prod_qntprice;
				if (is_gift_card) {
					prod_qntprice = getUpdatedGiftCardTotal(qnt);
				} else {
					prod_qntprice = number_format(qnt*prod_price, input.data('decimals'), input.data('decimal_separator'), input.data('thousand_separator'));
				}
				var prod_qnthtmlprice = input.data('price_format').replace('%2$s', prod_qntprice).replace('%1$s', input.data('currency'));

				if (is_gift_card) {
					$('#gift-card-quantity .prod-price .woocommerce-Price-amount.amount, .gift_card .prod-li-cont .prod-li-total-wrap .prod-li-total .woocommerce-Price-amount.amount').text(prod_qnthtmlprice);
				} else {
					if ($(this).parents('.sectls').find('.prod-li-total').length > 0) {
						$(this).parents('.sectls').find('.prod-li-total').html(prod_qnthtmlprice);
					}
					if ($(this).parents('.prod-cont').find('.prod-total-wrap .prod-price').length > 0) {

						$(this).parents('.prod-cont').find('.prod-total-wrap .prod-price').html(prod_qnthtmlprice);

						if ($(this).parents('.prod-cont').find('.prod-add form.cart input.qty').length > 0) {
							$(this).parents('.prod-cont').find('.prod-add form.cart input.qty').val($(this).parent().find('input').attr('value'));
						}

					}
				}
			}

			if ($('.cart-list').length > 0) {
				motor_ajax_cart( $(this).parent().find('.qty') );
			}

			return false;
		});

		if ($('.cart-list').length > 0) {
			$('input.qty').on('keypress', function(e) {
				// Pressing Enter
				if(e.which == 13) {
					var qnt = $(this).parent().find('input').val();
					if (qnt > 0) {
						$(this).parent().find('input').attr('value', qnt);
						motor_ajax_cart( $(this).parent().find('.qty') );
					}
					$(this).trigger('blur');
					return false;
				}
			});
		}



		// Section Product Title in Hover
		$('body')
			.on( "mouseenter", '.prod-items:not(.prod-items-notroll) .prod-i', function() {
				var ttl_height = $(this).find('h3').height();
				var inner_height = $(this).find('h3 span').height();
				$(this).find('h3 span').css('top', (-inner_height+ttl_height));
			})
			.on( "mouseleave", '.special, .popular, .sectgl', function() {
				$(this).find('h3 span').css('top', 0);
			});


		// Masonry Grids
		if ($('#blog-grid').length > 0) {
			$('#blog-grid').isotope({
				itemSelector: '.blog-grid-i',
			});
		}
		if ($('#gallery-grid').length > 0) {

			var $grid = $('#gallery-grid').isotope({
				itemSelector: '.gallery-grid-i',
			});
			$('#gallery-sections').on('click', 'a', function() {
				var filterValue = $( this ).attr('data-section');
				$grid.isotope({ filter: filterValue });
			});
			$('#gallery-sections').each( function( i, buttonGroup ) {
				var $buttonGroup = $( buttonGroup );
				$buttonGroup.on('click', 'a', function() {
					$buttonGroup.find('.active').removeClass('active');
					$( this ).addClass('active');
					return false;
				});
			});

		}
		if ($('.motor-gallery').length > 0) {

			var $grid = $('.motor-gallery').isotope({
				itemSelector: '.gallery-grid-i',
			});
			$('.motor-gallery-sections').on('click', 'a', function() {
				var filterValue = $( this ).attr('data-section');
				$grid.isotope({ filter: filterValue });
			});
			$('.motor-gallery-sections').each( function( i, buttonGroup ) {
				var $buttonGroup = $( buttonGroup );
				$buttonGroup.on('click', 'a', function() {
					$buttonGroup.find('.active').removeClass('active');
					$( this ).addClass('active');
					return false;
				});
			});

		}
		if ($('#about-gallery').length > 0) {
			$('#about-gallery').isotope({
				itemSelector: '.grid-item',
				columnWidth: '.grid-sizer',
				percentPosition: true
			});
		}





		// Ajaxify compare button
		$(document).on( 'click', '.prod-li-compare-btn', function(e){
			e.preventDefault();

			var button = $(this);

			if (button.hasClass('prod-li-compare-added'))
				return false;

			if (!button.hasClass('loading')) {

				button.parent().find('.prod-li-compare-loading').fadeIn();

				$.ajax({
					type: 'post',
					url: $(this).attr('href'),
					success: function(response){

						button.removeClass('loading').addClass('prod-li-compare-added');
						button.parent().find('.prod-li-compare-loading').fadeOut();

						$( '#compare-popup-message' ).remove();
						$( 'body' ).append( '<div id="compare-popup-message">' + button.data('added') + '</div>' );
						$( '#compare-popup-message' ).css( 'margin-left', '-' + $( '#compare-popup-message' ).width() + 'px' ).fadeIn();
						window.setTimeout( function() {
							$( '#compare-popup-message' ).fadeOut();
						}, 2000 );


						// Updating Compare Count
						if ($('#h-compare-count').length > 0) {
							var compare_count = $('#h-compare-count').text();
							compare_count++;
							$('#h-compare-count').text(compare_count);
							if ($('#h-compare-count').length > 0) {
								$('#h-compare-count').text(compare_count);
							} else {
								$('.header-compare').append('<span id="h-compare-count">' + compare_count + '</span>');
							}
						}
						if ($('#h-personal-compare-count').length > 0) {
							var compare_count_personal = $('#h-personal-compare-count').text();
							compare_count_personal++;
							$('#h-personal-compare-count').text(compare_count_personal);
						}
					}
				});
			}
		});




		$('.special-more').on('click', '.special-more-btn', function () {
			var button = $(this);
			var max_num_pages = button.attr('data-max-num-pages');
			var page_num = button.attr('data-page-num');
			var posts_per_page = button.attr('data-posts_per_page');
			var container = button.parent().parent().find(button.attr('data-container'));
			var url = button.attr('data-url');
			var file = button.attr('data-file');
			var order = button.attr('data-order');
			var orderby = button.attr('data-orderby');
			var view_mode = button.attr('data-view-mode');
			var item = button.attr('data-item');
			var ids = button.attr('data-ids');

			if (!button.hasClass('loading')) {

				button.addClass('loading');

				$.ajax({
					type: "POST",
					data: {
						posts_per_page : posts_per_page,
						page_num: page_num,
						order: order,
						orderby: orderby,
						view_mode: view_mode,
						file: file,
						ids: ids,
						action: 'motor_load_more'
					},
					url: url,
					success: function(data){
						$(button).removeClass('loading');
						if (max_num_pages <= page_num) {
							$(button).remove();
						}

						page_num++;
						button.attr('data-page-num', page_num);

						var btn_new = $(data).find('.' + button.attr('class'));
						var posts_list = $(data).find(item);
						container.append(posts_list);
						button.attr('data-max-num-pages', btn_new.attr('data-max-num-pages'));
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$(button).remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});
			}
			return false;
		});


		$('.cart-actions').on('click', '#shipping-cart-methods tr th', function () {
			if ($(this).hasClass('opened')) {
				$(this).removeClass('opened');
				$('#shipping_method').slideUp();
			} else {
				$(this).addClass('opened');
				$('#shipping_method').slideDown();
			}
			if ($('.shipping-calculator-form').css('display') == 'block') {
				$('.shipping-calculator-form').fadeOut();
			}
			return false;
		});
		if ($('#shipping_method').length > 0) {
			$('#shipping-cart-methods tr th').addClass('dropdown-list');
		}
		$( document.body ).on( 'updated_cart_totals', function() {
			if ($('#shipping_method').length > 0) {
				$('#shipping-cart-methods tr th').addClass('dropdown-list');
			}
		});



		// Gallery "Show More"
		$('.motor-gallery-more').on('click', 'a', function () {
			var button = $(this);
			var max_num_pages = button.attr('data-max-num-pages');
			var page_num = button.attr('data-page-num');
			//var container = button.attr('data-container');
			var container = button.parent().parent().find(button.attr('data-container'));
			var url = button.attr('data-url');
			var file = button.attr('data-file');
			var orderby = button.attr('data-orderby');
			var order = button.attr('data-order');
			var posts_per_page = button.attr('data-posts_per_page');
			var item = button.attr('data-item');
			var category = button.attr('data-category');

			if (!button.hasClass('loading')) {

				button.addClass('loading');

				$.ajax({
					type: "POST",
					data: {
						page_num: page_num,
						file: file,
						orderby: orderby,
						order: order,
						posts_per_page: posts_per_page,
						category: category,
						action: 'motor_gallery_load_more'
					},
					url: url,
					success: function(data){
						$(button).removeClass('loading');
						if (max_num_pages <= page_num) {
							$(button).remove();
						}
						var posts_list = $(data).find(item);

						container.append(posts_list).each(function(){
							container.isotope('reloadItems');
						});

						var $grid = container.isotope({
							itemSelector: item,
							layoutMode: 'masonry'
						});
						$grid.imagesLoaded().progress(function () {
							$grid.isotope('layout');
						});

						page_num++;
						button.attr('data-page-num', page_num);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$(button).remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});
			}
			return false;
		});


		if ($('.cont-sections').length > 0) {
			if ($(window).width() > 1203) {
				var menu_sections = $('.cont-sections');
				var menu_width = menu_sections.width();
				var menu_items_width = 0;
				menu_sections.find('> li').each(function () {
					if (!$(this).hasClass('cont-sections-more')) {
						menu_items_width = menu_items_width + ($(this).outerWidth(true) + 4);
						if (menu_width < menu_items_width) {
							$(this).addClass('cont-sections-other');
							$(this).appendTo('.cont-sections-sub');
						} else if ($(this).hasClass('cont-sections-other')) {
							$(this).removeClass('cont-sections-other');
							$(this).prependTo('.cont-sections-sub');
						}
					}
				});
				if (menu_width < menu_items_width) {
					$('.cont-sections-more').show();
				}
			}

			$('.cont-sections').addClass('sections-show');

			$(window).resize(function() {
				var menu_sections = $('.cont-sections');
				var menu_width = menu_sections.width();
				var menu_items_width = 0;
				if ($(window).width() > 1203) {
					menu_sections.find('> li').each(function () {
						menu_items_width = menu_items_width + ($(this).outerWidth(true) + 4);
						if (!$(this).hasClass('cont-sections-more')) {
							if (menu_width < menu_items_width) {
								$(this).addClass('cont-sections-other');
								$(this).appendTo('.cont-sections-sub');
							} else if ($(this).hasClass('cont-sections-other')) {
								$(this).removeClass('cont-sections-other');
								$(this).prependTo('.cont-sections-sub');
							}
						}
					});
					if (menu_width < menu_items_width) {
						$('.cont-sections-more').show();
					}
				} else {
					menu_sections.find('li.cont-sections-other').insertBefore('.cont-sections-more');
					menu_sections.find('li.cont-sections-other').removeClass('cont-sections-other');
				}
			});

		}




		// Quick View
		$('.maincont').on('click', '.quick-view', function () {
			var button = $(this);
			var product_id = $(this).data('id');
			var file = $(this).data('file');
			var url = $(this).data('url');
			if (!button.hasClass('loading')) {

				button.addClass('loading');

				$.ajax({
					type: "POST",
					data: {
						file: file,
						product_id: product_id,
						action: 'motor_quick_view'
					},
					url: url,
					success: function(data){
						$(button).removeClass('loading');
						$.fancybox({
							content: data,
							padding: 0,
							helpers : {
								overlay : {
									locked  : false
								}
							}
						});

						motor_initslider(true, '.quick-view-modal');

					},
					error: function(jqXHR, textStatus, errorThrown) {
						$(button).remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});
			}
			return false;
		});


		// Sticky header
		if ($('.header-sticky').length > 0) {
			$(window).scroll(function () {
				var topbar = false;
				var topbar_ht = $('.site-header-before').height();
				if ($('.site-header-before').length > 0 && $('.site-header-before').css('display') !== 'none') {
					topbar = true;
				}
				if (topbar) {
					$('body').css('margin-top', '0px');
					if (topbar_ht < $(window).scrollTop()) {
						$('.header-sticky .header').addClass('header_sticky');
						$('.site-header-before').css('margin-bottom', $('.header-sticky .header').outerHeight());
					} else {
						$('.header-sticky .header').removeClass('header_sticky');
						$('.site-header-before').css('margin-bottom', '0px');
					}
				} else {
					$('.header-sticky .header').addClass('header_sticky');
					$('body').css('margin-top', $('.header-sticky .header').outerHeight());
				}
			});
		}


	});
})(jQuery);

jQuery(document).ready(function(){
	var $ = jQuery;
	
	var cbt_wpadmin = {
	

		click_handlers: function()
		{
			// cache clear button for builder
			new ST_Pb_Settings({
				ajaxurl: cbt_admin_vars.ajaxurl,
				_nonce: cbt_admin_vars.ajax_nonce,
				button: "st-pb-clear-cache",
				button: "st-pb-clear-cache",
				loading: "#st-pb-clear-cache .layout-loading",
				message: $("#st-pb-clear-cache").parent().find(".layout-message"),
			});

	        // trigger window resize to force recalculation 
	        $('body').delegate('.st-trigger-window-resize', 'click', function(e) {
	            $(window).trigger('resize');
	        });
	        
			//var cbt_wpadmin.item;
			$('.cbt_select_icon').live('click',function ()
			{
				cbt_wpadmin.item = $(this).data('item');
				cbt_dialog('loader');
				cbt_wpadmin.load_icons_screen();
				return false;
			});

			// Remove visual composer activation notice as it causes confusion for customers
			if($('.uavc-activation-notice').length!=0)
				$('.uavc-activation-notice').click();
				
				
			$('#cbt-colors-link-popular, #cbt-colors-link-recent, .cbt-colors-link-back, .cbt-colors-link-next').live('click',function ()
			{
				var url = $(this).attr('rel');
				cbt_wpadmin.get_colors(url);
				return false;
			});
			
			
			$('#cbt-colors-search').click(function ()
			{
				cbt_wpadmin.color_search();
				return false;
			});
			
			$('#cbt-colors-search-term').live("keypress", function(e) 
			{
				if(e.keyCode == 13) 
				{
					cbt_wpadmin.color_search();
					return false;
				}
			});
			
			$('#cbt-activate-license').live('click', function ()
			{
				cbt_dialog('loader');
				var key = $('#cbt_license_key').val();

				jQuery.ajax(
				{
					url: ajaxurl,
					type: 'post',
					data: {action: 'cbt_activate_license', key: key},
					success: function(data)
					{                
						cbt_dialog(data);
					}
				});

				return false;
			});
			
			$('#cbt_license_key').live("keypress", function(e) 
			{
				if(e.keyCode == 13) 
				{
					$('#cbt-activate-license').click();
					return false;
				}
			});

			$(document).on('click', '.cbt_btn_ajax', function ()
			{
				var msg = $(this).data('msg'),
					proceed = true;

				if(msg)
				{
					proceed = confirm( msg );
				}

				if(proceed === false)
				{
					return false;
				}

				cbt_dialog('loader');

				jQuery.ajax(
				{
					url: ajaxurl,
					type: 'post',
					data: { action: $(this).data('ajax-action') },
					success: function(data)
					{                
						cbt_dialog(data);
					}
				});

				return false;
			});
		},
		
		
		color_search: function ()
		{
			var url = ajaxurl;
			
			url += '?action=cbt_colors_query&cbt_colors_action=search';
			url += '&page=1&search=';
			
			url += $('#cbt-colors-search-term').val();
			
			cbt_wpadmin.get_colors(url);
		},
		
		
		get_colors: function (url)
		{
			cbt_dialog('loader');
		
			$.ajax(
            {
                url: url,
                type: 'get',
                success: function(data)
                {
                	if( $('#cbt-swatches-holder .cbt-colors-ajax').length == 0 )
                	{
	                	cbt_dialog();
	                	$('#cbt-swatches-holder').html(data);
	                	return false;
                	}
                
                	$('#cbt-swatches-holder .cbt-colors-ajax').fadeOut(function ()
                	{
                		$(this).remove();
                		cbt_dialog();
                	
                		$('#cbt-swatches-holder').append(data);
                		
                		$('body').trigger('colors_scheme_field_init');
                	});
				}
			});
		},
		
		
		load_icons_screen: function()
		{
			var current = '';
		
			jQuery.ajax(
			{
				url: ajaxurl,
				type: 'post',
				data: {action: 'cbt_get_icons', current: current},
				success: function(data)
				{                
					cbt_dialog(data);
					
					$('#cbt_icon_selector .cbt-selectable').click(function()
					{
						$('#edit-menu-item-icon-'+cbt_wpadmin.item).val( $(this).data('value') );
						$('#edit-menu-item-icon-preview-'+cbt_wpadmin.item).removeAttr('class').addClass( $(this).data('value') );
						
						cbt_dialog(false);
						return false;
					});
				}
			});
		},
		
		page_metabox: function ()
		{
			if($('div#page_settings.postbox').length<1) return;
			
			// ------------------------------------------------------
			//  fields
			// ------------------------------------------------------
			var map_height_field = $('.cmb2-id-cbt-map-height');
			var sidebar_field = $('.cmb2-id-cbt-sidebar');
			
			map_height_field.hide();
			sidebar_field.hide();
			
			var template_field = $('#page_template');
			template_switch(template_field.val());
			
			template_field.change(function ()
			{
				template_switch(template_field.val());
			});
			
			
			function template_switch(template)
			{
				switch(template)
				{
					case 'template-home-sidebar.php':
					case 'template-sidebar.php':
						sidebar_field.show();
						map_height_field.hide();
					break;
					
					case 'template-contact.php':
						map_height_field.show();
						sidebar_field.hide();
					break;
					
					default:
						sidebar_field.hide();
						map_height_field.hide();
					break;
				}
			}
		},
		
		
		api: function ()
		{
			if($('.cbt_api_request').length<1) return;
			
			var elm, api, nonce, callback_event;
			$('.cbt_api_request').each(function ()
			{
				elm = $(this);
				api = elm.data('api');
				nonce = elm.data('nonce');
				callback_event = elm.data('callback-event');
				
				cbt_wpadmin.wp_ajax(
					{ action: 'cbt_api_request', api: api, nonce: nonce }, 
					elm,
					function (event_name)
					{
						if(event_name)
							$('body').trigger(event_name);
					},
					callback_event
				);
			});
		},
		
		wp_ajax: function (data, target, callback, event_name)
		{
			jQuery.ajax(
			{
				url: ajaxurl,
				type: 'post',
				data: data,
				success: function(data)
				{
					$(target).html( data );

					if(callback && event_name) callback(event_name);
					else if(callback) callback();
				}
			});
		},
		
		unbranded: function ()
		{
			if(! $('body').hasClass('cbt-unbranded')) return;
			
			$('#toplevel_page_cbt_options .wp-menu-image.dashicons-before').replaceWith('<div class="wp-menu-image dashicons-before dashicons-admin-settings"><br></div>');
			
			if($('body').hasClass('cbt-panel-locked'))
			{
				$('#toplevel_page_cbt_options .wp-submenu').remove();
			}
			
			var name = 'Theme System';

			$('#toplevel_page_cbt_options > a > .wp-menu-name').text(name);
			$('#toplevel_page_wr-pb-settings > a > .wp-menu-name').text('Theme Builder');
			$('#toplevel_page_wr-pb-settings .wp-submenu').remove();
			$('.cbt-branding-remove').remove();
			
			// ------------------------------------------------------
			//  per page unbranding
			// ------------------------------------------------------
			
			// dashboard
			if($('body').hasClass('index-php'))
			{
				$("#wp-version-message").remove();
			}
			
			// themes page
			if($('body').hasClass('plugins-php'))
			{
				$('tr#prostyler-builder .plugin-title strong').text('Theme Builder');
				$('tr#prostyler-builder .plugin-description p').text('Wordpress drag and drop page builder.');
				$('tr#prostyler-builder .plugin-version-author-uri').cbt_replaceText(' | By','').cbt_replaceText('|','');
			}
			
			// theme options panel page
			if($('body').hasClass('toplevel_page_cbt_options'))
			{
				$("#redux-intro-text p").cbt_replaceText( "ProStyler", "Theme" );
			}
			
			// builder admin page
			if($('body').hasClass('toplevel_page_wr-pb-settings'))
			{
				$(".wrap > h2").cbt_replaceText( "ProStyler", "Theme" );
				$("table.form-table p.description").cbt_replaceText( "ProStyler", "Theme" );
			}
		}

	};
	
	$('body').on('colors_scheme_field_init', function ()
	{
		$('#cbt_options-color-schemes').addClass('redux-field-init');
		redux.field_objects.image_select.init();	
	});

	init();
	
	
	// ------------------------------------------------------
	//  Run on page load as well as after every ajax call.
	// ------------------------------------------------------
	function init()
	{
		cbt_wpadmin.click_handlers();
		cbt_wpadmin.page_metabox();
		cbt_wpadmin.api();
		cbt_wpadmin.unbranded();
	}
});




// Declare Builder Upgrade class
(function($) {
	ST_Pb_Settings = function(params) {
		// Object parameters
		this.params = $.extend({}, params);

        $(document).ready($.proxy(function() {
			var params_ = this.params;
			// Get update button object
			this.button = document.getElementById(params_.button);

			// Set event handler to update product
			$(this.button).click($.proxy(function(event) {
				event.preventDefault();
				this.clear_cache(params_);
			}, this));
		}, this));
	};

	// Declare methods
	ST_Pb_Settings.prototype = {
		clear_cache: function(params_) {
			var button     = $('#' + params_.button);
			var cache_html = button.html();
            var loading    = $(params_.loading);
            var message    = params_.message;
            
            loading.toggleClass('hidden');
            loading.show();
            button.addClass("disabled").attr("disabled", "disabled");
			$.post(
                params_.ajaxurl,
                {
                    action 		: 'igpb_clear_cache',
                    st_nonce_check : params_._nonce
                },
                function(data) {
                	loading.hide();
                    message.html(data).toggleClass('hidden');
                    var text_change = button.data('textchange');
                    button.text(text_change);
                    setTimeout(function(){
                        message.toggleClass('hidden');
                        button.removeClass("disabled").removeAttr("disabled");
                        button.html(cache_html);
                    }, 1000 );
                }
            );
		}
	};
})(jQuery);



// ------------------------------------------------------
//  Model window, to display loading as well as any content
// ------------------------------------------------------
var cbt_popup, cbt_overlay, cbt_dialog_initialised = false;
function cbt_dialog(data)
{
    if (!cbt_dialog_initialised)
    {
        cbt_popup = jQuery('<div></div>').attr('id', 'cbt_popup').click(function(e)
        {
            e.stopPropagation();
        });
        cbt_overlay = jQuery('<div></div>').attr('id', 'cbt_overlay').click(function()
        {
            jQuery(this).fadeOut(500);
        });
        cbt_overlay.append(cbt_popup);
        jQuery('body').append(cbt_overlay);
        cbt_dialog_initialised = true;
    }
    cbt_overlay.fadeIn(500);
    
    if(!data)
    {
    	cbt_overlay.click();
    	return;
    }
    
    if(data) 
    {
    	if(data == 'loader') data = '<div class="cbt_loader"></div>';
    	jQuery('#cbt_popup').html(data);
    }
}


if (! jQuery.fn.cbt_replaceText) {
/*
 * jQuery cbt_replaceText - v1.1 - 11/21/2009
 * http://benalman.com/projects/jquery-cbt_replaceText-plugin/
 * 
 * Copyright (c) 2009 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($){$.fn.cbt_replaceText=function(b,a,c){return this.each(function(){var f=this.firstChild,g,e,d=[];if(f){do{if(f.nodeType===3){g=f.nodeValue;e=g.replace(b,a);if(e!==g){if(!c&&/</.test(e)){$(f).before(e);d.push(f)}else{f.nodeValue=e}}}}while(f=f.nextSibling)}d.length&&$(d).remove()})}})(jQuery);

}
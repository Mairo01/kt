/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function (e) {
	"use strict";
	var n = window.MENU_JS || {};
    var isLateralNavAnimating = false;

		n.mobileMenu = {
			init: function () {
				this.toggleMenu(), this.menuArrow()
			},
			toggleMenu: function () {
                e('.navigation-trigger').on('click', function(event){
                    event.preventDefault();
                    if( !isLateralNavAnimating ) {
                        if(e(this).parents('.csstransitions').length > 0 ) isLateralNavAnimating = true;

                        e('body').toggleClass('navigation-is-open');
                        e('.navigation-wrapper').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                            isLateralNavAnimating = false;
                        });
                    }
                });
				e('.main-navigation').on('click', '.menu-container .menu a i', function (event) {
					event.preventDefault();
					var ethis = e(this),
						eparent = ethis.closest('li'),
						esub_menu = eparent.find('> .sub-menu');
					if (esub_menu.css('display') == 'none') {
						esub_menu.slideDown('300');
						ethis.addClass('active');
					} else {
						esub_menu.slideUp('300');
						ethis.removeClass('active');
					}
					return false;
				});

			    if ( e( 'body' ).hasClass( 'menu-container' ) ) {
			        if ( ! e( this ).parents( '.main-navigation' ).length ) {
			           e('.menu-container').focus();
			        }
			    }
			},

			menuArrow: function () {
				if (e('.main-navigation .menu-container .menu').length) {
					e('.main-navigation .menu-container .menu .sub-menu').parent('li').find('> a').append('<i class="icon-down">');
				}
			}
		},

		e(document).ready(function () {
			n.mobileMenu.init();
		})
})(jQuery);
/*!
 * Visitor Login Notice
 */

/**
 * @summary     Visitor Login Notice
 * @description Display a login notice to your website's visitors.
 * @version     1.0.0
 * @file        assets/js/visitor-login-notice.js
 * @author      Maria Akritidou <maria@2squared.io>
 *
 */

(function ($) {
	'use strict';

	$(document).ready(function ($) {

		// When the close button is clicked, make the notice disappear
		$('#vln-close-btn').click(hide_notice_bar);

		// Also make the notice disappear, if the user has defined a custom class to close the notice
		if(ajaxObject.vln_options && ajaxObject.vln_options.close_trigger_class != '') {
			let close_trigger_class = ajaxObject.vln_options.close_trigger_class;
			if($('.' + close_trigger_class)) {
				$('.' + close_trigger_class).click(hide_notice_bar);
			}
		}

		function hide_notice_bar() {
			$('#visitor-login-notice').fadeOut('slow');

			if(typeof (Cookies) === 'undefined') {
				return false;
			}

			if(!$('#visitor-login-notice').is()) {
				// Add the cookie.
				if(Cookies.get('visitor_login_notice_cookie') == null) {
					Cookies.set('visitor_login_notice_cookie', 1, {expires: parseInt(ajaxObject.vln_cookie_length)});
				}
			}
			return false;
		}

		// If the cookie exists, don't show the notice bar
		if(typeof (Cookies) !== 'undefined' && !Cookies.get('visitor_login_notice_cookie')) {
			$('#visitor-login-notice').show();
		}
	});
})(jQuery);

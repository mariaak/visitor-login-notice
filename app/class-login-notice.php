<?php
/**
 * Class for showing a log in notice to non-logged in users.
 *
 * @package Login_Notice
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'Login_Notice' ) ) {

	/**
	 * Class for the plugin's core.
	 */
	class Login_Notice {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

            $this->register_hook_callbacks();
		}

        /**
         * Register hook callbacks
         *
         * @return void
         */
        public function register_hook_callbacks() {

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );
            add_action( 'wp_footer', array( $this, 'add_notice_section' ), 100 );

        }

		/**
		 * Enqueue style/script.
		 *
		 * @return void
		 */
		public function enqueue_style_scripts() {

			// Custom plugin script.
			wp_enqueue_style(
				'visitor-login-notice-style',
				TS_VLN_URL . 'assets/css/visitor-login-notice.css',
				'',
                TS_VLN_VERSION
			);

			// Register plugin's JS script
			wp_register_script(
				'visitor-login-notice-script',
				TS_VLN_URL . 'assets/js/visitor-login-notice.js',
				array(
					'jquery',
				),
				TS_VLN_VERSION,
				true
			);


	        // Provide a global object to our JS file containing the AJAX url and security nonce
	        wp_localize_script( 'visitor-login-notice-script', 'ajaxObject',
	            array(
	                'ajax_url'      => admin_url('admin-ajax.php'),
	                'ajax_nonce'    => wp_create_nonce('ajax_nonce'),
					//'plugin_url'	=> plugins_url('/', __FILE__),
	            )
	        );
	        wp_enqueue_script( 'visitor-login-notice-script');

		}

        /**
         *  Check if the notice should be shown
         *
         * @return boolean
         */
        private function should_notice_be_shown() {
            $page_slug = trim( $_SERVER["REQUEST_URI"] , '/' );

            // Bail out if logged in or registration page
            if ( is_user_logged_in() || strstr( $page_slug, 'register') ) {
                return false;
            }

            return true;
        }

        /**
         * Add a div that will contain the message and links
         *
         * @return HTML|boolean
         */
        public function add_notice_section() {
            global $wp;

            // Bail out if the member has already logged in
            if ( ! $this->should_notice_be_shown() ) {
                return false;
            }

            include( TS_VLN_PATH . '/views/notice-template.php' );
        }
	}

	new Login_Notice();
}

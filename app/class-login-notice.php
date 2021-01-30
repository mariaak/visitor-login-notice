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
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Holds the cookie duration for hiding notice when closed by user
         */
        const COOKIE_LENGTH = 365;

        /**
         * Constructor for class.
         */
        public function __construct() {

            if ( ! class_exists( 'Login_Notice_Options' ) ) {
                return;
            }

            $this->options = Login_Notice_Options::fetch();
            $this->register_hook_callbacks();
        }

        /**
         * Register hook callbacks
         *
         * @return void
         */
        public function register_hook_callbacks() {

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );

            /*
             * Display notice section on header / footer section depending on user option
            */
            if ( isset( $this->options['position'] ) && $this->options['position'] == 'header' ) {
                $display_hook = 'wp_head';
            } else {
                $display_hook = 'wp_footer';
            }
            add_action( $display_hook, array( $this, 'add_notice_section' ), 100 );

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

            // Enqueue JS Cookie library
            wp_enqueue_script(
                'js-cookie',
                TS_VLN_URL . 'assets/js/js.cookie.min.js',
                array(),
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
                    'ajax_url'          => admin_url( 'admin-ajax.php' ),
                    'ajax_nonce'        => wp_create_nonce( 'ajax_nonce' ),
                    'vln_options'       => get_option( 'vln_options' ),
                    'vln_cookie_length' => apply_filters( 'login_notice_cookie_length', self::COOKIE_LENGTH )
                )
            );
            wp_enqueue_script( 'visitor-login-notice-script' );

        }

        /**
         *  Check if the notice should be shown
         *
         * @return boolean
         */
        private function should_notice_be_shown() {

            $login_url    = isset( $this->options['login_url'] ) ? rtrim( $this->options['login_url'], '/' ) : wp_login_url();
            $register_url = isset( $this->options['register_url'] ) ? rtrim( $this->options['register_url'], '/' ) : wp_registration_url();
            $current_url  = vln_get_current_url();

            $exclude_urls = array();
            if ( isset( $this->options['exclude_urls'] ) ) {
                $exclude_urls = vln_extract_urls_from_string( $this->options['exclude_urls'] );
            }

            $show_notice = true;

            // Bail out if logged in
            if ( is_user_logged_in() ) {
                $show_notice = false;
            }
            // or login page is excluded
            if ( $this->options['exclude_login'] == '1' && $current_url == $login_url ) {
                $show_notice = false;
            }
            // or registration page is excluded
            if ( $this->options['exclude_register'] == '1' && $current_url == $register_url ) {
                $show_notice = false;
            }
            // or current url is added to excluded urls
            if ( in_array( $current_url, $exclude_urls ) ) {
                $show_notice = false;
            }

            return apply_filters( 'login_notice_should_notice_be_shown', $show_notice );
        }

        /**
         * Add a div that will contain the message and links
         *
         * @return HTML|boolean
         */
        public function add_notice_section() {

            // Bail out if the member has already logged in
            if ( ! $this->should_notice_be_shown() ) {
                return false;
            }

            $options = $this->options;

            include( TS_VLN_PATH . '/views/login-notice-view.php' );
        }
    }

    new Login_Notice();
}

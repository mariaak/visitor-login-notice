<?php
/**
 * Class for managing the login notice options.
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
if ( ! class_exists( 'Login_Notice_Options' ) ) {

	/**
	 * Class for the plugin's core.
	 */
	class Login_Notice_Options {

        /**
         * Holds the values to be used in the fields callbacks
         */
        private static $options;

        /**
         * Establish initial values for all options
         *
         * @return array
         */
        public static function get_default() {
            return [
                'position'				=> 'footer',
                'message_heading'		=> '',
                'message'				=> 'Join us to get access to more features.',
                'login_url'				=> '',
                'register_url'			=> '',
                'custom_text'			=> '',
                'custom_url'			=> '',
                'exclude_login'			=> 1,
                'exclude_register'		=> 1,
                'exclude_urls'			=> '',
                'allow_close'			=> 1,
                'close_trigger_class' 	=> '',
                'primary_bg_color'		=> '#dc3545',
                'secondary_bg_color' 	=> '#6f42c1',
                'primary_btn_color'		=> '#0099ff',
                'secondary_btn_color' 	=> '#ffffff'
            ];
        }

        /**
         * Retrieve values
         *
         * @return array $options
         */
        public static function fetch() {
            if ( !isset ( self::$options ) ) {
                self::$options = get_option( 'vln_options', self::get_default() );
            }

            return self::$options;
        }
	}
}

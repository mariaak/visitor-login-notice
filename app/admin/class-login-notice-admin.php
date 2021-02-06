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
if ( ! class_exists( 'Login_Notice_Admin' ) ) {

    /**
     * Class for the plugin's core.
     */
    class Login_Notice_Admin {

        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Constructor for class.
         */
        public function __construct() {

            // Check if the request is for an admin page and not running on the front-end (AJAX requests)
            if ( is_admin() && ! wp_doing_ajax() ) {
                $this->options = Login_Notice_Options::fetch();
                $this->register_hook_callbacks();
            }
        }

        /**
         * Register hook callbacks
         *
         * @return void
         */
        public function register_hook_callbacks() {

            add_action( 'admin_menu', array( $this, 'create_menu' ), 11 );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );
        }

        /**
         * Enqueue styles/scripts for back-end.
         *
         * @return void
         */
        public function enqueue_style_scripts() {

            // Custom plugin script.
            if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'vln_plugin_opts' ) {
                return;
            }

            wp_enqueue_style(
                'visitor-login-notice-style-admin',
                TS_VLN_URL . 'assets/css/visitor-login-notice-admin.css',
                '',
                TS_VLN_VERSION
            );

            // Add the color picker css & js files
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );

            wp_register_script(
                'visitor-login-notice-admin-script',
                TS_VLN_URL . 'assets/js/visitor-login-notice-admin.js',
                array(
                    'wp-color-picker',
                ),
                TS_VLN_VERSION,
                true
            );

            wp_enqueue_script( 'visitor-login-notice-admin-script' );
        }

        /**
         * Register menu options
         *
         * @return void
         */
        public function create_menu() {

            add_options_page(
                'Visitor Login Notice Settings',
                'Visitor Login Notice',
                'manage_options',
                'vln_plugin_opts',
                array( $this, 'vln_plugin_opts_page' ),
                80
            );
        }

        /**
         * Display plugin settings page
         *
         * @return void
         */
        public function vln_plugin_opts_page() {

            ?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Visitor Login Notice Settings', 'login-notice' ); ?></h1>
				<form method="post" action="options.php" id="vln_settings_form">
                    <?php
                    settings_fields( 'vln_options_group' );
                    do_settings_sections( 'vln_plugin_opts' );
                    submit_button();
                    ?>
				</form>
			</div>
            <?php
        }

        /**
         * Register plugin settings
         *
         * @return void
         */
        public function admin_init() {

            register_setting(
                'vln_options_group',
                'vln_options'
            );

            add_settings_section(
                'vln_section',
                '',
                array( $this, 'vln_section_cb' ),
                'vln_plugin_opts'
            );

            add_settings_field(
                'position',
                __( 'Position', 'login-notice' ),
                array( $this, 'vln_position_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'message_heading',
                __( 'Title', 'login-notice' ),
                array( $this, 'vln_message_heading_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'message',
                __( 'Message', 'login-notice' ),
                array( $this, 'vln_message_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'login_url',
                __( 'Custom Login Address (URL)', 'login-notice' ),
                array( $this, 'vln_login_url_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'register_url',
                __( 'Custom Registration Address (URL)', 'login-notice' ),
                array( $this, 'vln_register_url_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'custom_text',
                __( 'Custom Button Text', 'login-notice' ),
                array( $this, 'vln_custom_text_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'custom_url',
                __( 'Custom Button Address (URL)', 'login-notice' ),
                array( $this, 'vln_custom_url_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'exclude_login',
                __( 'Exclude Login URL', 'login-notice' ),
                array( $this, 'vln_exclude_login_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'exclude_register',
                __( 'Exclude Registration URL', 'login-notice' ),
                array( $this, 'vln_exclude_register_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'exclude_urls',
                __( 'Exclude Custom URLs', 'login-notice' ),
                array( $this, 'vln_exclude_urls_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'allow_close',
                __( 'Show close button', 'login-notice' ),
                array( $this, 'vln_allow_close_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'close_trigger_class',
                __( 'Class to trigger close event (CSS)', 'login-notice' ),
                array( $this, 'vln_close_trigger_class_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'primary_bg_color',
                __( 'Primary background color', 'login-notice' ),
                array( $this, 'vln_primary_bg_color_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'secondary_bg_color',
                __( 'Secondary background color', 'login-notice' ),
                array( $this, 'vln_secondary_bg_color_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'primary_btn_color',
                __( 'Primary button color', 'login-notice' ),
                array( $this, 'vln_primary_btn_color_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );

            add_settings_field(
                'secondary_btn_color',
                __( 'Secondary button color', 'login-notice' ),
                array( $this, 'vln_secondary_btn_color_cb' ),
                'vln_plugin_opts',
                'vln_section'
            );
        }

        public function vln_section_cb() {
            echo '<p>You can change the settings here.</p>';
        }

        public function vln_position_cb() {

            echo "<input type='radio' name='vln_options[position]' value='header' " . ( isset( $this->options['position'] ) && $this->options['position'] == 'header' ? "checked='checked'" : "" ) . "> <label for='vln_options[position]'> " . __( 'Header', 'login-notice' ) . "</label>";
            echo " <input type='radio' name='vln_options[position]' value='footer' " . ( isset( $this->options['position'] ) && $this->options['position'] == 'footer' ? "checked='checked'" : "" ) . "> <label for='vln_options[position]'> " . __( 'Footer', 'login-notice' ) . "</label>";
        }

        public function vln_message_heading_cb() {

            $val = isset( $this->options['message_heading'] ) ? esc_attr( $this->options['message_heading'] ) : '';
            echo "<input type='text' name='vln_options[message_heading]' class='regular-text' value='" . $val . "'>" . __( '
 Leave it blank, if you do not need a title', 'login-notice' );
        }

        public function vln_message_cb() {

            $val = isset( $this->options['message'] ) ? esc_html( $this->options['message'] ) : '';
            echo "<textarea name='vln_options[message]' rows='5' cols='50' class='large-text'>" . $val . "</textarea>";
        }

        public function vln_login_url_cb() {

            $val = isset( $this->options['login_url'] ) ? esc_url( $this->options['login_url'] ) : '';
            echo "<input type='url' name='vln_options[login_url]' class='regular-text' value='" . $val . "'>" . __( '
 Leave it blank, if you want to use the default login URL', 'login-notice' );
        }

        public function vln_register_url_cb() {

            $val = isset( $this->options['register_url'] ) ? esc_url( $this->options['register_url'] ) : '';
            echo "<input type='url' name='vln_options[register_url]' class='regular-text' value='" . $val . "'>" . __( '
 Leave it blank, if you want to use the default registration URL', 'login-notice' );
        }

        public function vln_custom_text_cb() {

            $val = isset( $this->options['custom_text'] ) ? esc_attr( $this->options['custom_text'] ) : '';
            echo "<input type='text' name='vln_options[custom_text]' class='regular-text' value='" . $val . "'>" . __( '
 Leave it blank, if you do not need a custom button', 'login-notice' );
        }

        public function vln_custom_url_cb() {

            $val = isset( $this->options['custom_url'] ) ? esc_url( $this->options['custom_url'] ) : '';
            echo "<input type='url' name='vln_options[custom_url]' class='regular-text' value='" . $val . "'>" . __( '
 Leave it blank, if you do not need a custom button', 'login-notice' );
        }

        public function vln_exclude_login_cb() {

            echo "<input type='checkbox' name='vln_options[exclude_login]' value='1' " . ( ! empty( $this->options['exclude_login'] ) ? "checked='checked'" : '' ) . ">" . __( 'Check to hide the notice on the login page', 'login-notice' );
        }

        public function vln_exclude_register_cb() {

            echo "<input type='checkbox' name='vln_options[exclude_register]' value='1' " . ( ! empty( $this->options['exclude_register'] ) ? "checked='checked'" : '' ) . ">" . __( 'Check to hide the notice on the registration page', 'login-notice' );
        }

        public function vln_exclude_urls_cb() {

            $val = isset( $this->options['exclude_urls'] ) ? esc_html( $this->options['exclude_urls'] ) : '';
            echo "<textarea name='vln_options[exclude_urls]' rows='5' cols='50' class='large-text'>" . $val . "</textarea>"
                 . __( 'Enter each URL in a separate line', 'login-notice' );;
        }

        public function vln_allow_close_cb() {

            echo "<input type='checkbox' name='vln_options[allow_close]' value='1' " . ( ! empty( $this->options['allow_close'] ) ? "checked='checked'" : '' ) . ">" . __( 'Check to display a close button', 'login-notice' ) .
                 "<div class='vln-important-note'>" . __( '<b>Important note</b>: To support the close button functionality and retain the user\'s choice, a cookie is created and stored on the user\'s browser. When the cookie is present, the notice will not appear again on that browser. The cookie expiration time is set to 365 days. Please consider including this information to your privacy policy.', 'login-notice' ) . "</div>";
        }

        public function vln_close_trigger_class_cb() {

            $val = isset( $this->options['close_trigger_class'] ) ? esc_attr( $this->options['close_trigger_class'] ) : '';
            echo "<input type='text' name='vln_options[close_trigger_class]' class='regular-text' value='" . $val . "'> " . __( 'Optionally, enter the CSS class of an element, that when clicked will close the notice', 'login-notice' );
        }

        public function vln_primary_bg_color_cb() {

            $val = isset( $this->options['primary_bg_color'] ) ? $this->options['primary_bg_color'] : '';
            echo '<input type="text" name="vln_options[primary_bg_color]" value="' . $val . '" class="vln-color-picker" >';
        }

        public function vln_secondary_bg_color_cb() {

            $val = ( isset( $this->options['secondary_bg_color'] ) ) ? $this->options['secondary_bg_color'] : '';
            echo '<input type="text" name="vln_options[secondary_bg_color]" value="' . $val . '" class="vln-color-picker" >';
        }

        public function vln_primary_btn_color_cb() {

            $val = isset( $this->options['primary_btn_color'] ) ? $this->options['primary_btn_color'] : '';
            echo '<input type="text" name="vln_options[primary_btn_color]" value="' . $val . '" class="vln-color-picker" >';
        }

        public function vln_secondary_btn_color_cb() {

            $val = ( isset( $this->options['secondary_btn_color'] ) ) ? $this->options['secondary_btn_color'] : '';
            echo '<input type="text" name="vln_options[secondary_btn_color]" value="' . $val . '" class="vln-color-picker" >';
        }

    }

    new Login_Notice_Admin();
}

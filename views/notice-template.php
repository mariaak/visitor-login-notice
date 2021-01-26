<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

?>

<div id="visitor-login-notice">
	<div class="visitor-login-text">
        <?php _e( 'Join us now to get access to more features!','login-notice' );
        ?>
	</div>
	<div class="visitor-login-actions">
		<a href="<?php echo wp_login_url( home_url( $wp->request ) ); ?>"
		   class="button login-action login-link">
            <?php _e( 'Log In', 'login-notice' ); ?>
		</a>
		<a href="<?php echo wp_registration_url(); ?>" class="button login-action sign-up-link">
            <?php _e( 'Sign Up', 'login-notice' ); ?>
		</a>
	</div>
</div>

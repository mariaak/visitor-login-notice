<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$login_url    = isset ( $options['login_url'] ) ? $options['login_url'] : wp_login_url();
$register_url = isset ( $options['register_url'] ) ? $options['register_url'] : wp_registration_url();

?>

<?php if ( isset( $options['message'] ) ) { ?>

<div id="visitor-login-notice" class="vln-<?php echo $options['position']; ?>" style="background: linear-gradient(90deg, <? echo $options['primary_bg_color']; ?>  0%, <? echo $options['secondary_bg_color']; ?> 100%);">
    <?php if ( isset ( $options['allow_close'] ) && $options['allow_close'] == '1' ) { ?>
		<a href="#" id="vln-close-btn" aria-hidden="true"></a>
    <?php } ?>

	<div class="vln-text">
        <?php if ( isset( $options['message_heading'] ) ) { ?>
			<span class="vln-message-heading">
				<?php echo esc_html( $options['message_heading'] ); ?>
			</span>
        <?php } ?>

        <?php if ( isset( $options['message'] ) ) { ?>
			<p class="vln-message">
                <?php echo esc_html( $options['message'] ); ?>
			</p>
        <?php } ?>
	</div>

	<div class="vln-actions">

		<a href="<?php echo esc_url( $options['login_url'] ); ?>"
		   class="vln-btn vln-login-btn" style="background: <? echo $options['primary_btn_color']; ?>">
            <?php _e( 'Log In', 'login-notice' ); ?>
		</a>

		<a href="<?php echo esc_url( $options['register_url'] ); ?>" class="vln-btn vln-register-btn" style="background: <? echo $options['secondary_btn_color']; ?>">
            <?php _e( 'Sign Up', 'login-notice' ); ?>
		</a>

        <?php if ( ! empty( $options['custom_text'] ) && ! empty ( $options['custom_url'] ) ) { ?>
		<a href="<?php echo esc_url( $options['custom_url'] ); ?>" class="vln-btn vln-custom-btn" style="background: <? echo $options['secondary_btn_color']; ?>>
        <?php echo esc_attr( $options['custom_text'] ); ?>
				</a>
        <?php } ?>
				</div>
				</div>

        <?php } ?>

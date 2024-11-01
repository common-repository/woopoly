<?php
if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>

<h3><?php _e( 'WooPoly Plugin', 'woopoly' ); ?></h3>
<p>
    <?php
    _e( 'WooPoly Plugin has deactivated itself because WooCommerce and/or Polylang are no longer active. Please install and activate both plugins before activating this plugin.', 'woopoly' );
    ?>
<p>
<hr>

<?php _e( 'Download Plugins: ', 'woopoly' ); ?>
<a href="https://wordpress.org/plugins/woocommerce/">
    <?php _e('WooCommerce', 'woopoly'); ?>
</a>
|
<a href="https://wordpress.org/plugins/polylang/">
    <?php _e('Polylang', 'woopoly'); ?>
</a>

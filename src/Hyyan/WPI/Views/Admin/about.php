<?php
if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>
<h3>
    <span>
        <?php _e('About this Plugin', 'woopoly'); ?>
    </span>
</h3>
<div class="inside">
    <div>
        <p>
            <?php
            _e(
               'This plugin is an open source project which aims to fill the gap between
               <a href="https://wordpress.org/plugins/woocommerce/">Woocommerce</a>
               and <a href="https://wordpress.org/plugins/polylang/">Polylang</a>',
               'woopoly'
            );
            ?>
        </p>
        <p>
            <?php
            _e(
               'This is a fork of the <a href="https://wordpress.org/plugins/woo-poly-integration/">
                Hyyan WooCommerce Polylang Integration</a> plugin develop by Hyyan
                that is no longer mantained. Credit goes to Hyyan for this great
                plugin.',
                'woopoly'
            );
            ?>
        </p>
        <p>
            <?php _e('Author: ', 'woopoly') ?>
            <a href="https://github.com/decarvalhoaa">Antonio de Carvalho</a>
        </p>

        <?php echo \Hyyan\WPI\Plugin::getView('badges'); ?>

    </div>
</div>

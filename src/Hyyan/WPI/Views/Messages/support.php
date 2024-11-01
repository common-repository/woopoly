<?php
if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="20%" align="middle" style="vertical-align: central">
            <img src="http://www.gravatar.com/avatar/bf50a40dc528b0be4a95ab34567b7699?s=150"
                 style="padding: 2% ;vertical-align: top"/>
        </td>
        <td style="padding-left: 2% ; padding-right: 2%">
            <h3><?php _e( 'WooPoly Plugin', 'woopoly' ); ?> </h3>

            <p><?php echo \Hyyan\WPI\Plugin::getView('badges'); ?></p>

            <p>
                <?php
                _e(
                   'Hello, my name is <b>Antonio de Carvalho</b>, and I am the developer
                   of <b>WooPoly</b> plugin.',
                   'woopoly'
                );
                ?>
            </p>

            <p>
                <?php
                _e(
                   'This plugin is a fork of the <a href="https://wordpress.org/plugins/woo-poly-integration/">
                    Hyyan WooCommerce Polylang Integration</a> plugin develop by Hyyan
                    that is no longer mantained. Credit goes to Hyyan for this great
                    plugin.',
                    'woopoly'
                );
                ?>
            </p>

            <p>
                <?php
                _e(
                   'If you find this plugin useful, please write a few words about it
                   at the <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/woo-poly-integration">wordpress.org</a>
                   or <a target="_blank" href="https://twitter.com">twitter</a>. It will
                   help other people find this plugin more quickly.<br><br>

                   Thank you!',
                   'woopoly'
                );
                ?>
            </p>
            <hr>
            <?php echo \Hyyan\WPI\Plugin::getView('social') ?>
        </td>
    </tr>
</table>

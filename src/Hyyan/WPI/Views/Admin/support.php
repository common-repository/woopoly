<?php
if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>
<h3>
    <span>
        <?php _e( 'Support the Plugin', 'woopoly' ); ?>
    </span>
</h3>
<div class="inside">
    <p>
        <?php
        _e(
           '<strong>This plugin is free and it will always be free.</strong>
           Keeping it up to date and supporting its users takes a lot of effort
           and time.',
           'woopoly'
        );
        ?>
    </p>
    <p>
        <?php
        _e(
           'You can support the future development of this plugin and keep the
           users forum alive by making a small donation.',
           'woopoly'
        );
        ?>
    </p>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="YKMDTB62QYJUC">
        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>

    <p>
        <?php
        _e(
           'If donations i not your thing, please support by rating the plugin on
           <a href="https://wordpress.org/support/view/plugin-reviews/woopoly">Wordpress Repository</a> ,
           or by giving it a star on <a href="https://github.com/decarvalhoaa/woopoly">Github</a>.',
           'woopoly'
        );
        ?>
    </p>
    <p>
        <?php echo \Hyyan\WPI\Plugin::getView('social') ?>
    </p>
</div>

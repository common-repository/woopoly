<?php
if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>
<h3
    ><span><?php _e('Need help?', 'woopoly'); ?></span>
</h3>

<div class="inside">
    <p>
        <?php
        _e( 'Need help? Want to suggest a new feature? Get in touch', 'woopoly' );
        ?>
    </p>
    <ol>
        <li>
            <a href="https://github.com/decarvalhoaa/woopoly/issues" target="_blank">
                <?php _e('On Github', 'woopoly'); ?>
            </a>
        </li>
        <li>
            <a href="https://wordpress.org/support/plugin/woopoly" target="_blank">
                <?php _e('On Wordpress Support Forum', 'woopoly'); ?>
            </a>
        </li>
        <li>
            <a href="mailto:decarvalhoaa@gmail.com" target="_blank">
                <?php _e('On Email', 'woopoly'); ?>
            </a>
        </li>
    </ol>
</div>

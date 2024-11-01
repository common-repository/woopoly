<?php

if (!defined('ABSPATH')) {
    exit('restricted access');
}
?>

<?php

printf(
        __('You can translate WooCommerce endpoints from Polylang Strings Transalations
           tab. <a target="_blank" href="%s">%s</a>', 'woopoly'),
        add_query_arg(
                array(
                    'page' => 'mlang',
                    'tab' => 'strings',
                    'group' => \Hyyan\WPI\Endpoints::getPolylangStringSection()
                ),
                admin_url( 'options-general.php' )
        ),
        __( 'Translate', 'woopoly' )
);
?>

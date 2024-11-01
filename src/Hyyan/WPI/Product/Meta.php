<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI\Product;

use Hyyan\WPI\HooksInterface,
    Hyyan\WPI\Utilities,
    Hyyan\WPI\Admin\Settings,
    Hyyan\WPI\Admin\MetasList;

/**
 * product Meta
 *
 * Handle product meta sync
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class Meta
{

    /**
     * Construct object
     */
    public function __construct()
    {
        // sync product meta
        add_action( 'current_screen', array( $this, 'syncProductsMeta' ) );
    }

    /**
     * Sync porduct meta
     *
     * @return false if the current post type is not "product"
     */
    public function syncProductsMeta()
    {

        // sync product meta with polylang
        add_filter('pll_copy_post_metas', array(__CLASS__, 'getProductMetaToCopy'));

        $currentScreen = get_current_screen();
        if ( $currentScreen->post_type !== 'product' ) {
            return false;
        }

        $ID = false;
        $disable = false;

        /*
         * Disable editing product meta for product translations
         *
         * In case of a "Add or update product" ($GET['post'] is set), and the
         * product language is different from the default, it is a product translation
         * and editing the product metadata should be disabled.
         *
         * In case of a "Add product translation" ($GET['new_lang'] is set), or the
         * 'edit' page, editing product metadata should be disabled.
         */
        if ( isset( $_GET['post'] ) ) { // Add or update product

            $ID      = absint($_GET['post']);
            $disable = $ID && ( pll_get_post_language( $ID ) != pll_default_language() );

        } elseif ( isset( $_GET['new_lang'] ) || $currentScreen->base == 'edit' ) { // Add product translation

            $ID      = isset( $_GET['from_post'] ) ? absint( $_GET['from_post'] ) : false;
            $disable = isset( $_GET['new_lang'] ) && ( esc_attr( $_GET['new_lang'] ) != pll_default_language() ) ? true : false;

            // Add the '_translation_porduct_type' meta,for the case the product
            // was created before plugin acivation.
            $this->add_product_type_meta( $ID );

        }

        // disable fields edit for product translations
        if ( $disable ) {
            add_action( 'admin_print_scripts', array( $this, 'addFieldsLocker' ), 100 );
        }

        // sync the product type selection in the product data settings box
        $this->sync_product_type_selection( $ID );

    }

    /**
     * Add product type meta to products created before plugin activation
     *
     * @param int $id   Id of the product in the default language
     */
    public function add_product_type_meta( $id) {
        if ( $id ) {
            $meta = get_post_meta( $id, '_translation_porduct_type' );

            if ( empty( $meta ) ) {
                $product = wc_get_product( $id );
                if ( $product ) {
                    update_post_meta( $id, '_translation_porduct_type', $product->product_type );
                }
            }

        }
    }

    /**
     * Define the meta keys that must copyied from orginal product to its
     * translation
     *
     * @param array   $metas array of meta keys
     * @param boolean $flat  false to return meta list with sections (default true)
     *
     * @return array extended meta keys array
     */
    public static function getProductMetaToCopy(array $metas = array(), $flat = true)
    {

        $default = apply_filters(HooksInterface::PRODUCT_META_SYNC_FILTER, array(
            // general
            'general' => array(
                'name' => __('General Metas', 'woopoly'),
                'desc' => __('General Metas', 'woopoly'),
                'metas' => array(
                    'product-type',
                    '_virtual',
                    '_downloadable',
                    '_sku',
                    '_regular_price',
                    '_sale_price',
                    '_sale_price_dates_from',
                    '_sale_price_dates_to',
                    '_downloadable_files',
                    '_download_limit',
                    '_download_expiry',
                    '_download_type',
                    'menu_order',
                    'comment_status',
                    '_upsell_ids',
                    '_crosssell_ids',
                    '_featured',
                    '_thumbnail_id',
                    '_price',
                    '_product_image_gallery',
                    'total_sales',
                    '_translation_porduct_type',
                    '_visibility',
                )
            ),
            // stock
            'stock' => array(
                'name' => __('Stock Metas', 'woopoly'),
                'desc' => __('Stock Metas', 'woopoly'),
                'metas' => array(
                    '_manage_stock',
                    '_stock',
                    '_backorders',
                    '_stock_status',
                    '_sold_individually',
                )
            ),
            // shipping
            'shipping' => array(
                'name' => __('Shipping Metas', 'woopoly'),
                'desc' => __('Shipping Metas', 'woopoly'),
                'metas' => array(
                    '_weight',
                    '_length',
                    '_width',
                    '_height',
                    'product_shipping_class',
                )
            ),
            // attributes
            'Attributes' => array(
                'name' => __('Attributes Metas', 'woopoly'),
                'desc' => __('Attributes Metas', 'woopoly'),
                'metas' => array(
                    '_product_attributes',
                    '_default_attributes',
                ),
            ),
            // Taxes
            'Taxes' => array(
                'name' => __('Taxes Metas', 'woopoly'),
                'desc' => __('Taxes Metas', 'woopoly'),
                'metas' => array(
                    '_tax_status',
                    '_tax_class',
                ),
            )
        ));

        if (false === $flat) {
            return $default;
        }

        foreach ($default as $ID => $value) {
            $metas = array_merge( $metas, Settings::getOption( $ID, MetasList::getID(), $value['metas'] ) );
        }

        return array_values($metas);
    }

    /**
     * Add the Fields Locker script
     *
     * The script will disable editing of some porduct metas for product
     * translation
     *
     * @return boolean false if the fields locker feature is disabled
     */
    public function addFieldsLocker()
    {

        if ('off' === Settings::getOption('fields-locker', \Hyyan\WPI\Admin\Features::getID(), 'on')) {
            return false;
        }

        $metas = static::getProductMetaToCopy();
        $selectors = apply_filters(HooksInterface::FIELDS_LOCKER_SELECTORS_FILTER, array(
            '.insert',
            in_array('_product_attributes', $metas) ? '#product_attributes :input' : rand(),
        ));

        $jsID = 'product-fields-locker';
        $code = sprintf(
                'var disabled = %s;'
                . 'for (var i = 0; i < disabled.length; i++) {'
                . ' $('
                . '     %s + ","'
                . '     + "." + disabled[i] + ","'
                . '     + "#" +disabled[i] + ","'
                . '     + "*[name^=\'"+disabled[i]+"\']"'
                . ' )'
                . '     .off("click")'
                . '     .on("click", function (e) {e.preventDefault()})'
                . '     .css({'
                . '         opacity: .5,'
                . '         \'pointer-events\': \'none\','
                . '         cursor: \'not-allowed\''
                . '     }'
                . ' );'
                . '}'
                , json_encode($metas)
                , !empty($selectors) ?
                        json_encode(implode(',', $selectors)) :
                        array(rand())
        );

        Utilities::jsScriptWrapper($jsID, $code);
    }

    /**
     * Sync the product type selection (e.g. Simple product, Grouped product, Variable
     * product, etc ) in the dropdown list in the Product Data settings box
     *
     * @param integer $ID Product Id
     */
    protected function sync_product_type_selection( $ID = null )
    {
        /*
         * First we add save_post action to save the product type
         * as post meta
         *
         * This is step is important so we can get the right product type
         */
        add_action( 'save_post', function ( $_ID ) {

            $product = wc_get_product( $_ID );
            if ( $product && ! isset( $_GET['from_post'] ) ) {
                update_post_meta( $_ID, '_translation_porduct_type', $product->product_type );
            }

        });

        /*
         * If the _translation_porduct_type meta is found then we add the
         * js script to sync the product type selection
         *
         * TODO: Change the product type in the DB instead with
         * wp_set_object_terms( $product_id, 'the new product type', 'product_type' );
         *
         */
        if ( $ID && ( $type = get_post_meta( $ID, '_translation_porduct_type' ) ) ) {

            add_action( 'admin_print_scripts', function () use ( $type ) {

                $jsID = 'product-type-sync';
                $code = sprintf(
                        '// <![CDATA[ %1$s'
                        . ' addLoadEvent(function () { %1$s'
                        . '  jQuery("#product-type option")'
                        . '     .removeAttr("selected");%1$s'
                        . '  jQuery("#product-type option[value=\"%2$s\"]")'
                        . '         .attr("selected", "selected");%1$s'
                        . '})'
                        . '// ]]>'
                        , PHP_EOL
                        , $type[0]
                );

                Utilities::jsScriptWrapper( $jsID, $code, false );
            }, 11 );
        }
    }

}

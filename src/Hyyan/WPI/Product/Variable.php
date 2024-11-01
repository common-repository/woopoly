<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI\Product;

use Hyyan\WPI\HooksInterface;
use Hyyan\WPI\Utilities;

/**
 * Variable
 *
 * Handle Variable Products
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class Variable
{

    /**
     * Construct object
     */
    public function __construct()
    {
        // Handle variations duplication
        add_action( 'save_post', array( $this, 'duplicateVariations' ), 10, 3 );
        add_action( 'save_post', array( $this, 'sync_default_attributes' ), 10, 3 );

        // Remove variations
        add_action( 'wp_ajax_woocommerce_remove_variations', array( $this, 'removeVariations' ), 9 );

        // Extend meta list to include variation meta and fields to lock
        add_filter( HooksInterface::PRODUCT_META_SYNC_FILTER, array( $this, 'extendProductMetaList' ) );
        add_filter( HooksInterface::FIELDS_LOCKER_SELECTORS_FILTER, array( $this, 'extendFieldsLockerSelectors' ) );

        // Variable Products limitations warnings and safe-guards
        if ( is_admin() ) {
            $this->handleVariableLimitation();
            $this->shouldDisableLangSwitcher();
        }
    }

    /**
     * Translate Variation for given variable product
     *
     * @param integer  $ID     product variable ID
     * @param \WP_Post $post   Product Post
     * @param boolean  $update true if update , false otherwise
     *
     * @return boolean
     */
    public function duplicateVariations($ID, \WP_Post $post, $update)
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        global $pagenow;
        if (!in_array($pagenow, array('post.php', 'post-new.php'))) {
            return;
        }

        $product = wc_get_product($ID);

        if (!$product) {
            return;
        }

        $from = null;

        if (pll_get_post_language($ID) == pll_default_language()) {
            $from = $product;
        } else {

            if (isset($_GET['from_post'])) {
                /*
                 * This check will make sure that variation , will be
                 * created for brand new products which are not saved yet by user
                 */
                $from = Utilities::getProductTranslationByID(
                                esc_attr($_GET['from_post'])
                                , pll_default_language()
                );
            } else {
                $from = Utilities::getProductTranslationByObject(
                                $product
                                , pll_default_language()
                );
            }
        }

        if (!($from instanceof \WC_Product_Variable)) {
            return;
        }

        $langs = pll_languages_list();

        foreach ($langs as $lang) {
            $variation = new Variation(
                    $from
                    , Utilities::getProductTranslationByObject($product, $lang)
            );

            remove_action('save_post', array($this, __FUNCTION__), 10);

            $variation->duplicate();

            add_action('save_post', array($this, __FUNCTION__), 10, 3);
        }

    }

    public function skip_default_attributes_meta( $check, $object_id, $meta_key, $meta_value ) {

        // Ignore if not default attribute
        if ( '_default_attributes' === $meta_key ) {

            $product = wc_get_product( $object_id );

            // Don't let anyone delete the meta. NO ONE!
            if ( $product && current_filter() === 'delete_post_metadata' ) {
                return false;
            }

            // _default_attributes meta should be unique
            if ( $product && current_filter() === 'add_post_metadata' ) {
                $old_value = get_post_meta( $product->id, '_default_attributes' );
                return empty( $old_value ) ? $check : false;
            }

            // Maybe is Variable Product
            // New translations of Variable Products are first created as simple
            if ( $product && Utilities::maybe_variable_product( $product ) ) {

                // Try Polylang first
                $lang = pll_get_post_language( $product->id );

                if ( ! $lang ) // Must be a new translation and Polylang doesn't stored the language yet
                    $lang = isset( $_GET['new_lang'] ) ? $_GET['new_lang'] : '';

                foreach ( $meta_value as $key => $value ) {
                    $term = get_term_by( 'slug', $value, $key );

                    if ( $term && pll_is_translated_taxonomy( $term->taxonomy ) ) {
                        $translated_term_id = pll_get_term( $term->term_id, $lang );
                        $translated_term    = get_term_by( 'id', $translated_term_id, $term->taxonomy );

                        // If meta is taxonomy managed by Polylang and is in the
                        // correct language process, otherwise return false to
                        // stop execution
                        return ( $value === $translated_term->slug ) ? $check : false;
                    }

                }

            }

        }

        return $check;

    }

    /**
     * Sync default attributes between product translations
     *
     * @param int       $post_id    Post ID
     * @param \WP_Post  $post       Post Object
     * @param boolean   $update     true if updating the post, false otherwise
     */
    public function sync_default_attributes( $post_id, $post, $update ) {

        // Don't sync if not in the admin backend nor on autosave
        if ( ! is_admin() &&  defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Don't sync if Default Attribute syncronization is disabled
        $metas = Meta::getProductMetaToCopy();

        if ( ! in_array( '_default_attributes', $metas ) ) {
            return;
        }

        //  To avoid Polylang overwriting default attribute meta
        add_filter( 'delete_post_metadata', array( $this, 'skip_default_attributes_meta' ), 10, 4 );
        add_filter( 'add_post_metadata', array( $this, 'skip_default_attributes_meta' ), 10, 4 );
        add_filter( 'update_post_metadata', array( $this, 'skip_default_attributes_meta' ), 10, 4 );

        // Don't sync if not a Variable Product
        $product = wc_get_product( $post_id );

        if ( $product && 'simple' === $product->product_type && Utilities::maybe_variable_product( $product ) ) {

            // Maybe is Variable Product
            // New translations of Variable Products are first created as simple

            // Only need to sync for the new translation from source product
            // The other product translation stay untouched
            $attributes_translation = Utilities::get_default_attributes_translation( $_GET['from_post'], $_GET['new_lang'] );

            if ( ! empty( $attributes_translation ) && isset( $attributes_translation[ $_GET['new_lang'] ] ) ) {
                update_post_meta( $product->id, '_default_attributes', $attributes_translation[ $_GET['new_lang'] ] );
            }

        } elseif ( $product && 'variable' === $product->product_type ) {

            // Variable Product

            // For each product translation, get the translated (default) terms/attributes
            $attributes_translation = Utilities::get_default_attributes_translation( $product->id );
            $langs                  = pll_languages_list();

            foreach ( $langs as $lang ) {

                $translation_id = pll_get_post( $product->id, $lang );

                if ( $translation_id != $product->id ) {
                    update_post_meta( $translation_id, '_default_attributes', $attributes_translation[ $lang ] );
                }

            }

        }
    }

    /**
     * Remove variations related to current removed variation
     */
    public function removeVariations()
    {
        if (isset($_POST['variation_ids'])) {
            $IDS = (array) $_POST['variation_ids'];

            foreach ($IDS as $ID) {
                Variation::deleteRelatedVariation($ID);
            }
        }
    }

    /**
     * Extend the product meta list that must by synced
     *
     * @param array $metas current meta list
     *
     * @return array
     */
    public function extendProductMetaList(array $metas)
    {

        $metas['Variables'] = array(
            'name' => __('Variables Metas', 'woopoly'),
            'desc' => __('Variables Metas', 'woopoly'),
            'metas' => array(
                '_min_variation_price',
                '_max_variation_price',
                '_min_price_variation_id',
                '_max_price_variation_id',
                '_min_variation_regular_price',
                '_max_variation_regular_price',
                '_min_regular_price_variation_id',
                '_max_regular_price_variation_id',
                '_min_variation_sale_price',
                '_max_variation_sale_price',
                '_min_sale_price_variation_id',
                '_max_sale_price_variation_id',
            )
        );

        return $metas;
    }

    /**
     * Extend the fields locker selectors
     *
     * Extend the fields locker selectors to lock variation fields for translation
     *
     * @param array $selectors
     *
     * @return array
     */
    public function extendFieldsLockerSelectors(array $selectors)
    {

        $selectors[] = '#variable_product_options :input';

        return $selectors;
    }

    /**
     * Handle variation limitation about default language
     *
     * @global string $pagenow current page name
     *
     * @return boolean false if this is not new variable product
     */
    public function handleVariableLimitation()
    {

        global $pagenow;
        if ($pagenow !== 'post-new.php') {
            return false;
        }

        if (isset($_GET['from_post'])) {
            return false;
        }

        if (pll_current_language() === pll_default_language()) {
            return false;
        }

        add_action('admin_print_scripts', function () {

            $jsID = 'variables-data';
            $code = sprintf(
                    'var HYYAN_WPI_VARIABLES = {'
                    . '     title       : "%s" ,'
                    . '     content     : "%s" ,'
                    . '     defaultLang : "%s"'
                    . '};'
                    , __('Wrong default language for Variable Products', 'woopoly')
                    , __("Variable products must be created in the default language first due to a plugin design limitation. <br> <a href='https://github.com/decarvalhoaa/woopoly/tree/master#what-you-need-to-know-about-this-plugin' target='_blank'>Read more</a>", 'woopoly' )
                    , pll_default_language()
            );

            Utilities::jsScriptWrapper($jsID, $code, false);
        });

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script("jquery-effects-core");
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script(
                    'woo-poly-variables'
                    , plugins_url('public/js/Variables.js', WOOPOLY_FILE )
                    , array('jquery', 'jquery-ui-core', 'jquery-ui-dialog')
                    , \Hyyan\WPI\Plugin::getVersion()
                    , true
            );
        }, 100);
    }

    /**
     * Check if we have to disable the language switcher in the polylang setting
     * page
     */
    public function shouldDisableLangSwitcher()
    {
        add_action('current_screen', function () {

            $screen = get_current_screen();
            if ($screen->id !== 'settings_page_mlang') {
                return false;
            }

            $count = wp_count_posts('product_variation');
            if (!($count && $count->publish > 0)) {
                return false;
            }

            add_action('admin_print_scripts', function () {

                $jsID = 'disable-lang-switcher';
                $code = sprintf(
                        '$("#options-lang #default_lang")'
                        . '.css({'
                        . '     "opacity": .5,'
                        . '     "pointer-events": "none"'
                        . '});'
                        . ' $("#options-lang").prepend('
                        . '     "<p class=\'update-nag\'>%s</p>"'
                        . ');'
                        , __('You can not change the default language, when adding variable products', 'woopoly')
                );
                Utilities::jsScriptWrapper($jsID, $code);
            }, 100);
        });
    }

}
<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI\Taxonomies;

use Hyyan\WPI\Admin\Settings,
    Hyyan\WPI\Admin\Features,
    Hyyan\WPI\Product\Meta,
    Hyyan\WPI\Utilities;

/**
 * Taxonomies
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class Taxonomies
{
    /**
     * Managed taxonomies
     *
     * @var array
     */
    protected $managed = array();

    /**
     * List of taxonomies to be copied/synced with exact same value
     *
     * @var array
     */
    public $tax_to_copy = array();

    /**
     * Construct object
     */
    public function __construct()
    {
        /* Just to prepare taxonomies  */
        $this->prepareAndGet();

        /*  List of taxonomies which will be filtered by language */
        add_filter( 'pll_get_taxonomies', array( $this, 'manageTaxonomiesTranslation' ) );

        if ( Utilities::woocommerce_version_check( '2.6' ) ) {

            /* List of taxonomies to be copied/synced with exact same value */
            $metas = Meta::getProductMetaToCopy();

            // Shipping Class taxonomy translation is not supported after WooCommerce 2.6
            if ( in_array( 'product_shipping_class', $metas ) ) {
                $this->tax_to_copy[] = 'product_shipping_class';
            }

            add_filter( 'pll_copy_taxonomies', array( $this, 'copy_taxonomies' ), 10, 2 );
        }

    }

    /**
     * Add untranslatable taxonomies to list of taxonomies to be copied/synced
     * with exact same value cross products and product translations
     *
     * @param array     $taxonomies List of taxonomy names
     * @param boolean   $sync       true if it is synchronization, false if it is a copy
     */
    public function copy_taxonomies( $taxonomies, $sync ) {
        $taxonomies = array_merge( $taxonomies, $this->tax_to_copy );
        return $taxonomies;
    }

    /**
     * Notifty polylang about product taxonomies
     *
     * @param array $taxonomies array of custom taxonomies managed by polylang
     *
     * @return array
     */
    public function manageTaxonomiesTranslation($taxonomies)
    {

        $supported = $this->prepareAndGet();
        $add = $supported[0];
        $remove = $supported[1];
        $options = get_option('polylang');

        $taxs = $options['taxonomies'];
        $update = false;

        foreach ($add as $tax) {
            if (!in_array($tax, $taxs)) {
                $options['taxonomies'][] = $tax;
                $update = true;
            }
        }
        foreach ($remove as $tax) {
            if (in_array($tax, $taxs)) {
                $options['taxonomies'] = array_flip($options['taxonomies']);
                unset($options['taxonomies'][$tax]);
                $options['taxonomies'] = array_flip($options['taxonomies']);
                $update = true;
            }
        }

        if ($update) {
            update_option('polylang', $options);
        }

        return array_merge($taxonomies, $add);
    }

    /**
     * Get managed taxonomies
     *
     * @return array taxonomies that must be added and removed to polylang
     */
    protected function prepareAndGet()
    {
        $add = array();
        $remove = array();
        $supported = array(
            'attributes' => 'Hyyan\WPI\Taxonomies\Attributes',
            'categories' => 'Hyyan\WPI\Taxonomies\Categories',
            'tags' => 'Hyyan\WPI\Taxonomies\Tags',
            'shipping-class' => 'Hyyan\WPI\Taxonomies\ShippingClass'    // For WC >= 2.6, Shipping Classes translation is set forced to 'off' in /Hyyyan/WPI/Admin/Features.php
        );                                                              // and will be removed from the taxomonies to be filteres by language, because Shipping Classes can no longer
                                                                        // be translated in Polylang

        foreach ($supported as $option => $class) {
            $names = $class::getNames();

            if ('on' === Settings::getOption($option, Features::getID(), 'on')) {
                $add = array_merge($add, $names);
                if (!isset($this->managed[$class])) {
                    $this->managed[$class] = new $class();
                }
            } else {
                $remove = array_merge($remove, $names);
            }
        }

        return array($add, $remove);
    }

}

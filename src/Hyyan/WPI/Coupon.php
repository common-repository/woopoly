<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI;

use Hyyan\WPI\Admin\Settings,
    Hyyan\WPI\Admin\Features;

/**
 * Coupon
 *
 * Handle coupon with products translations
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class Coupon
{

    /**
     * Construct object
     */
    public function __construct()
    {
        if ('on' === Settings::getOption('coupons', Features::getID(), 'on')) {
            add_action('woocommerce_coupon_loaded', array($this, 'couponLoaded'));
        }
    }

    /**
     * Extend the coupon to include porducts translations
     *
     * @param \WC_Coupon $coupon
     *
     * @return \WC_Coupon
     */
    public function couponLoaded( \WC_Coupon $coupon ) {

        $product_ids                  = array();
        $exclude_product_ids          = array();
        $product_categories_ids       = array();
        $exclude_product_category_ids = array();

        foreach ($coupon->product_ids as $id) {
            foreach ( $this->getProductPostTranslationIDS( $id ) as $_id ) {
                $product_ids[] = $_id;
            }
        }

        foreach ($coupon->exclude_product_ids as $id) {
            foreach ( $this->getProductPostTranslationIDS( $id ) as $_id ) {
                $exclude_product_ids[] = $_id;
            }
        }

        foreach ($coupon->product_categories as $id) {
            foreach ( $this->getProductTermTranslationIDS( $id ) as $_id ) {
                $product_categories_ids[] = $_id;
            }
        }

        foreach ($coupon->exclude_product_categories as $id) {
            foreach ( $this->getProductTermTranslationIDS( $id ) as $_id ) {
                $exclude_product_category_ids[] = $_id;
            }
        }

        $coupon->product_ids                = $product_ids;
        $coupon->exclude_product_ids        = $exclude_product_ids;
        $coupon->product_categories         = $product_categories_ids;
        $coupon->exclude_product_categories = $exclude_product_category_ids;

        return $coupon;
    }

    /**
     * Get array of product translations IDS
     *
     * @param integer $ID the product ID
     *
     * @return array array contains all translation IDS for the given product
     */
    protected function getProductPostTranslationIDS($ID)
    {
        $result = array($ID);
        $product = wc_get_product($ID);

        if ($product && $product->product_type === 'variation') {
            $IDS = Product\Variation::getRelatedVariation($ID, true);
            if (is_array($IDS)) {
                $result = array_merge($result, $IDS);
            }
        } else {
            $IDS = Utilities::getProductTranslationsArrayByID($ID);
            if (is_array($IDS)) {
                $result = array_merge($result, $IDS);
            }
        }

        return $IDS ? $IDS : array($ID);
    }

    /**
     * Get array of term translations IDS
     *
     * @param integer $ID the term ID
     *
     * @return array array contains all translation IDS for the given term
     */
    protected function getProductTermTranslationIDS($ID)
    {

        $IDS = Utilities::getTermTranslationsArrayByID($ID);

        return $IDS ? $IDS : array($ID);
    }

}

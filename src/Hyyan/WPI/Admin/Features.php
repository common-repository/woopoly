<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI\Admin;

use Hyyan\WPI\Utilities;

/**
 * Features
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class Features extends AbstractSettings
{

    /**
     * {@inheritdocs}
     */
    public static function getID()
    {
        return 'wpi-features';
    }

    /**
     * {@inheritdocs}
     */
    protected function doGetSections()
    {
        return array(
            array(
                'title' => __('Features', 'woopoly'),
                'desc' => __(
                        ' The section allows you to enable/disable the plugin features.'
                        , 'woopoly'
                )
            )
        );
    }

    /**
     * {@inheritdocs}
     */
    protected function doGetFields()
    {
        $fields = array(
            array(
                'name' => 'fields-locker',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Fields Locker', 'woopoly'),
                'desc' => __(
                        'Fields locker makes it easy for user to know which
                         field to translate and which to ignore '
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'emails',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Emails', 'woopoly'),
                'desc' => __(
                        'Use order language whenever woocommerce sends order emails'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'reports',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Reports', 'woopoly'),
                'desc' => __(
                        'Enable reports language filtering and combining'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'coupons',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Coupons Sync', 'woopoly'),
                'desc' => __(
                        'Apply coupons rules for products and their translations'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'stock',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Stock Sync', 'woopoly'),
                'desc' => __(
                        'Sync stock for products and their translations'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'categories',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Translate Categories', 'woopoly'),
                'desc' => __(
                        'Enable categories translation'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'tags',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Translate Tags', 'woopoly'),
                'desc' => __(
                        'Enable tags translation'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'attributes',
                'type' => 'checkbox',
                'default' => 'on',
                'label' => __('Translate Attributes', 'woopoly'),
                'desc' => __(
                        'Enable Attributes translation'
                        , 'woopoly'
                )
            ),
            array(
                'name' => 'shipping-class',
                'type' => 'checkbox',
                'default' => 'off',
                'label' => __('Translate Shipping Classes', 'woopoly'),
                'desc' => __(
                        'Enable Shipping Classes translation' . ( Utilities::woocommerce_version_check( '2.6' ) ? ' (not supported for WooCommerce versions >= 2.6)' : '' )
                        , 'woopoly'
                )
            )
        );

        return $fields;
    }

}

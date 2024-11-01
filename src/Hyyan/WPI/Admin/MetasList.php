<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI\Admin;

use Hyyan\WPI\Product\Meta;

/**
 * MetasList
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
class MetasList extends AbstractSettings
{

    /**
     * {@inheritdocs}
     */
    public static function getID()
    {
        return 'wpi-metas-list';
    }

    /**
     * {@inheritdocs}
     */
    protected function doGetSections()
    {
        return array(
            array(
                'title' => __('Metas List', 'woopoly'),
                'desc'  => __(
                              'The section will allow you to fine tune which metadata
                              should be synced between products and their translations.
                              The default values are appropriate for the large majority of
                              the users. It is safe to ignore these settings if you do not
                              understand their meaning.',
                              'woopoly'
                )
            )
        );
    }

    /**
     * {@inheritdocs}
     */
    protected function doGetFields()
    {

        /* Metas list */
        $metas = Meta::getProductMetaToCopy(array(), false);
        $fields = array();
        foreach ($metas as $ID => $value) {

            $fields[] = array(
                'name' => $ID,
                'label' => $value['name'],
                'desc' => $value['desc'],
                'type' => 'multicheck',
                'default' => array_combine($value['metas'], $value['metas']),
                'options' => array_combine(
                        $value['metas']
                        , array_map(array(__CLASS__, 'normalize'), $value['metas'])
                )
            );
        }

        return $fields;
    }

    /**
     * Normalize string by removing "_" from string
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalize($string)
    {
        return ucwords( trim( str_replace( '_', ' ', $string ) ) );
    }

}

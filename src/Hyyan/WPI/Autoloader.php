<?php

/**
 * This file is part of the hyyan/woo-poly-integration plugin.
 * (c) Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hyyan\WPI;

/**
 * Plugin namespace autoloader
 *
 * @author Hyyan Abo Fakher <tiribthea4hyyan@gmail.com>
 */
final class Autoloader
{
    /**
     * @var String
     */
    private $base;

    /**
     * Construct
     *
     * @param string $base the base path
     *
     * @throws \Exception when the autloader can not register itself
     */
    public function __construct( $base ) {
        $this->base = $base;
        spl_autoload_register( array( $this, 'autoload' ), true, true );
    }

    /**
     * Handles autoloading
     *
     * @param string $class_name class or inteface name
     *
     * @return boolean true if class or interface exists, false otherwise
     */
    public function autoload( $class_name )
    {
        if ( stripos( $class_name, 'Hyyan\WPI' ) === false ) {
            return;
        }

        $filename = $this->base . str_replace( '\\', '/', $class_name ) . '.php';
        if ( file_exists( $filename ) ) {
            require_once( $filename );
            if ( class_exists( $class_name ) || interface_exists( $class_name ) ) {
                return true;
            }
        }

        return false;
    }

}

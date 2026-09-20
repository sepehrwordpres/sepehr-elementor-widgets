<?php
namespace SEW;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Autoloader {
    public static function run() {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    public static function autoload( $class ) {
        if ( 0 !== strpos( $class, 'SEW\\' ) ) {
            return;
        }

        $relative_class = substr( $class, 4 );

        if ( 0 === strpos( $relative_class, 'Widgets\\' ) ) {
            $class_name = substr( $relative_class, 8 );
            $file = SEW_PLUGIN_PATH . 'widgets/' . str_replace( '\\', '/', $class_name ) . '.php';
        } else {
            $file = SEW_PLUGIN_PATH . 'includes/' . str_replace( '\\', '/', $relative_class ) . '.php';
        }

        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}
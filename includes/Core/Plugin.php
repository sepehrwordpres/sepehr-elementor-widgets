<?php
namespace SEW\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Plugin {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __construct() {

        /*
         * ثبت دسته‌بندی ویجت‌های SEW در Elementor
         */
        add_action(
            'elementor/elements/categories_registered',
            [ $this, 'add_widget_category' ]
        );

        /*
         * مدیریت Asset ها
         */
        new Asset_Manager();

        /*
         * مدیریت Widget ها + AJAX جدید Dynamic Post Grid Pro
         */
        new Widget_Manager();
    }

    /**
     * ثبت دسته‌بندی اختصاصی ویجت‌های SEW
     */
    public function add_widget_category( $elements_manager ) {

        $elements_manager->add_category(
            'sew-widgets',
            [
                'title' => esc_html__(
                    'Sepehr Widgets',
                    'sew'
                ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }
}
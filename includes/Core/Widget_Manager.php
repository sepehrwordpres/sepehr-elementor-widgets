<?php
namespace SEW\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Widget_Manager {
    public function __construct() {
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }

    private function get_widgets() {
        return [
            '\SEW\Widgets\Pricing_Table',
            '\SEW\Widgets\Testimonial_Carousel',
            '\SEW\Widgets\Image_Card',
            '\SEW\Widgets\Testimonial_Carousel_Pro',
            '\SEW\Widgets\Pricing_Table_Pro',
            '\SEW\Widgets\Dynamic_Post_Grid_Pro', // <-- SEW UPDATE: ثبت ویجت جدید Dynamic Post Grid Pro (پرانتزهای اضافه‌ی «()» که باعث fail شدن class_exists() می‌شد حذف شد)
        ];
    }

    public function register_widgets( $widgets_manager ) {
        $widgets = apply_filters( 'sew/widgets/registered', $this->get_widgets() );

        foreach ( $widgets as $widget ) {
            if ( class_exists( $widget ) ) {
                $widgets_manager->register( new $widget() );
            }
        }
    }
}
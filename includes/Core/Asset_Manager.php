<?php
namespace SEW\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Asset_Manager {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );

        // <-- SEW UPDATE: حذف شد — add_action( 'wp_ajax_sew_filter_posts', ... ) و
        // add_action( 'wp_ajax_nopriv_sew_filter_posts', ... )
        // دلیل: Dynamic_Post_Grid_Pro.php خودش هندلر AJAX امن با
        // action='sew_dpg_query' و nonce='sew_dpg_nonce' را در همان فایل
        // ثبت می‌کند (self-contained). نگه‌داشتن این هوک هم یعنی دو
        // اکشن AJAX موازی و ناهماهنگ با nonce متفاوت. جزئیات کامل پایین فایل.
    }

    public function register_styles(): void {
        $version = defined( 'SEW_VERSION' ) ? \SEW_VERSION : '1.1.0';

        wp_register_style(
            'sew-dynamic-post-grid-pro', // <-- SEW UPDATE: نام handle از 'sew-dynamic-post-grid' به 'sew-dynamic-post-grid-pro' تغییر کرد تا دقیقاً با چیزی که Dynamic_Post_Grid_Pro::get_style_depends() برمی‌گرداند یکی باشد (وگرنه Elementor فایل CSS را هیچ‌وقت روی صفحه لود نمی‌کرد)
            \SEW_PLUGIN_URL . 'assets/css/sew-dynamic-post-grid-pro.css',
            [],
            $version
        );

        wp_register_style(
            'sew-testimonial-carousel-pro-style',
            \SEW_PLUGIN_URL . 'assets/css/sew-testimonial-carousel-pro.css',
            [],
            $version
        );

        wp_register_style(
            'sew-pricing-table-pro-style',
            \SEW_PLUGIN_URL . 'assets/css/sew-pricing-table-pro.css',
            [],
            $version
        );
    }

    public function register_scripts(): void {
        $version = defined( 'SEW_VERSION' ) ? \SEW_VERSION : '1.1.0';

        wp_register_script(
            'sew-dynamic-post-grid-pro', // <-- SEW UPDATE: نام handle از 'sew-dynamic-post-grid' به 'sew-dynamic-post-grid-pro' تغییر کرد، دلیل مثل بالا (باید با get_script_depends() ویجت یکی باشد)
            \SEW_PLUGIN_URL . 'assets/js/sew-dynamic-post-grid-pro.js',
            [], // <-- SEW UPDATE: وابستگی 'jquery' حذف شد — sew-dynamic-post-grid-pro.js با Vanilla JS (fetch) نوشته شده و به jQuery نیازی ندارد
            $version,
            true
        );

        wp_localize_script( 'sew-dynamic-post-grid-pro', 'sewDpgVars', [ // <-- SEW UPDATE: هم handle (باید با بالا یکی باشد) و هم اسم متغیر JS از 'sewAjax' به 'sewDpgVars' تغییر کرد — چون sew-dynamic-post-grid-pro.js دقیقاً همین اسم را می‌خواند (window.sewDpgVars)
            'ajaxUrl' => admin_url( 'admin-ajax.php' ), // <-- SEW UPDATE: کلید از 'ajax_url' به 'ajaxUrl' تغییر کرد تا با camelCase مورد استفاده در JS هماهنگ باشد
            'nonce'   => wp_create_nonce( 'sew_dpg_nonce' ), // <-- SEW UPDATE: قبلاً nonce اصلاً ارسال نمی‌شد؛ چون هندلر AJAX ویجت با check_ajax_referer('sew_dpg_nonce','nonce') کار می‌کند، بدون این خط هر درخواست AJAX رد می‌شد
        ] );

        wp_register_script(
            'sew-testimonial-carousel-pro-script',
            \SEW_PLUGIN_URL . 'assets/js/sew-testimonial-carousel-pro.js',
            [ 'jquery', 'elementor-frontend' ],
            $version,
            true
        );
    }

    // <-- SEW UPDATE: متد ajax_fetch_posts() کامل حذف شد.
    //
    // سه دلیل داشت:
    //   1) این متد روی action='sew_filter_posts' و nonce='sew_grid_nonce'
    //      کار می‌کرد، در حالی‌که Dynamic_Post_Grid_Pro.php و JS آن
    //      (sew-dynamic-post-grid-pro.js) از action='sew_dpg_query' و
    //      nonce='sew_dpg_nonce' استفاده می‌کنند. این دو هیچ‌وقت به هم
    //      وصل نمی‌شدند.
    //   2) داخل این متد به \SEW\Widgets\Dynamic_Post_Grid::render_post_card()
    //      رفرنس داده شده بود — کلاسی به این اسم (بدون _Pro) در پروژه فعلی
    //      اصلاً وجود ندارد؛ اگر این هوک فعال می‌ماند و کسی درخواست
    //      sew_filter_posts می‌فرستاد، PHP Fatal Error می‌گرفتیم.
    //   3) نگه‌داشتن این کد یعنی دو مسیر AJAX موازی برای یک قابلیت —
    //      منطق Query (allowlist برای orderby/order و غیره) باید فقط
    //      در یک جا زندگی کند تا در آینده از هم جدا نیفتند.
    //
    // معادل امن و کامل این متد همین الان در
    // widgets/Dynamic_Post_Grid_Pro.php به صورت
    // Dynamic_Post_Grid_Pro::ajax_query() وجود دارد و روی
    // wp_ajax_sew_dpg_query / wp_ajax_nopriv_sew_dpg_query هوک شده.
    // اگر ترجیح می‌دهی این منطق اینجا در Asset_Manager متمرکز بماند
    // (هم‌راستا با الگوی قبلی‌ات)، بگو تا برعکسش کنم: هندلر را از
    // ویجت به این‌جا منتقل کنم و ویجت را به آن ارجاع بدهم.
}
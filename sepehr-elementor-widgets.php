<?php
/**
 * Plugin Name: Sepehr Elementor Widgets
 * Description: Core Architecture for Sepehr Elementor Widgets.
 * Plugin URI:  #
 * Version:     1.0.0
 * Author:      مصطفی اکبری
 * Text Domain: sew
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * مسیر و URL اصلی افزونه
 */
define( 'SEW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
 * Autoloader
 */
require_once SEW_PLUGIN_PATH . 'includes/Autoloader.php';

\SEW\Autoloader::run();

/*
 * ============================================================
 * AJAX: Dynamic Post Grid Pro
 * ============================================================
 *
 * این endpoint عمداً در فایل اصلی افزونه ثبت می‌شود.
 *
 * دلیل:
 * admin-ajax.php نباید برای پیدا کردن AJAX handler
 * به lifecycle المنتور وابسته باشد.
 *
 * کلاس Dynamic_Post_Grid_Pro هنگام اجرای callback
 * توسط Autoloader لود خواهد شد.
 */
add_action(
	'wp_ajax_sew_dpg_query',
	[ '\SEW\Widgets\Dynamic_Post_Grid_Pro', 'ajax_query' ]
);

add_action(
	'wp_ajax_nopriv_sew_dpg_query',
	[ '\SEW\Widgets\Dynamic_Post_Grid_Pro', 'ajax_query' ]
);

/*
 * ============================================================
 * Plugin Bootstrap
 * ============================================================
 */
add_action( 'plugins_loaded', 'sew_init_plugin' );

function sew_init_plugin() {

	/*
	 * Elementor باید فعال باشد تا Widget ها و
	 * قابلیت‌های Elementor افزونه اجرا شوند.
	 */
	if ( ! did_action( 'elementor/loaded' ) ) {

		add_action(
			'admin_notices',
			'sew_elementor_missing_notice'
		);

		return;
	}

	/*
	 * راه‌اندازی هسته افزونه
	 */
	\SEW\Core\Plugin::instance();
}

/**
 * پیام نبودن Elementor
 */
function sew_elementor_missing_notice() {

	echo '<div class="notice notice-error is-dismissible"><p>';

	echo esc_html__(
		'Sepehr Elementor Widgets requires Elementor to be installed and activated.',
		'sew'
	);

	echo '</p></div>';
}
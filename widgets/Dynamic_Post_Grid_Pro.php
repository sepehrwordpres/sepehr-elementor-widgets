<?php
namespace SEW\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Dynamic_Post_Grid_Pro
 *
 * Premium Elementor widget: AJAX-powered post grid with category
 * filtering, load-more pagination and a glassmorphism card design.
 *
 * @package SEW\Widgets
 */
class Dynamic_Post_Grid_Pro extends Widget_Base {

	/**
	 * Unique widget name.
	 */
	public function get_name() {
		return 'sew-dynamic-post-grid-pro';
	}

	/**
	 * Widget title shown in the Elementor panel.
	 */
	public function get_title() {
		return __( 'Dynamic Post Grid Pro', 'sew' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Widget category. Keep 'general' so this integrates safely
	 * regardless of any custom category already registered in
	 * Widget_Manager.php. Rename here if the project defines
	 * a dedicated SEW category (e.g. 'sew-widgets').
	 */
	public function get_categories() {
		return array( 'sew-widgets' );
	}

	public function get_keywords() {
		return array( 'post', 'grid', 'blog', 'ajax', 'filter', 'load more', 'sew' );
	}

	/**
	 * Script dependencies. The handle is registered by
	 * Asset_Manager.php (see registration notes) and localized
	 * with sew_dpg_vars (ajax_url + nonce).
	 */
	public function get_script_depends() {
		return array( 'sew-dynamic-post-grid-pro' );
	}

	/**
	 * Style dependencies. Handle registered by Asset_Manager.php.
	 */
	public function get_style_depends() {
		return array( 'sew-dynamic-post-grid-pro' );
	}

	/**
	 * Register all Content + Style controls.
	 */
	protected function register_controls() {
		$this->register_query_controls();
		$this->register_filter_content_controls();
		$this->register_load_more_content_controls();

		$this->register_card_style_controls();
		$this->register_image_style_controls();
		$this->register_title_style_controls();
		$this->register_excerpt_style_controls();
		$this->register_meta_style_controls();
		$this->register_filter_style_controls();
		$this->register_button_style_controls();
	}

	/* =====================================================
	 * CONTENT TAB — QUERY SETTINGS
	 * ===================================================== */
	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query Settings', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => __( 'Post Type', 'sew' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_public_post_types(),
			)
		);

		$this->add_responsive_control(
			'posts_per_page',
			array(
				'label'           => __( 'Posts Per Page', 'sew' ),
				'type'            => Controls_Manager::NUMBER,
				'min'             => 1,
				'max'             => 50,
				'step'            => 1,
				'default'         => 6,
				'tablet_default'  => 4,
				'mobile_default'  => 2,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'sew' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .sew-dpg-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'sew' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'     => __( 'Date', 'sew' ),
					'title'    => __( 'Title', 'sew' ),
					'modified' => __( 'Modified', 'sew' ),
					'rand'     => __( 'Random', 'sew' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'sew' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'ASC'  => __( 'ASC', 'sew' ),
					'DESC' => __( 'DESC', 'sew' ),
				),
			)
		);

		$this->add_control(
			'include_categories',
			array(
				'label'       => __( 'Include Categories', 'sew' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_category_options(),
			)
		);

		$this->add_control(
			'exclude_categories',
			array(
				'label'       => __( 'Exclude Categories', 'sew' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_category_options(),
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => __( 'Featured Image', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'   => __( 'Excerpt', 'sew' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length (words)', 'sew' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'   => __( 'Date', 'sew' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_read_more',
			array(
				'label'   => __( 'Read More Button', 'sew' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'     => __( 'Read More Text', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More', 'sew' ),
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * CONTENT TAB — CATEGORY FILTER
	 * ===================================================== */
	protected function register_filter_content_controls() {
		$this->start_controls_section(
			'section_filter_content',
			array(
				'label' => __( 'Category Filter', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_filter',
			array(
				'label'        => __( 'Enable Filter', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'filter_all_label',
			array(
				'label'     => __( '"All" Label', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'All', 'sew' ),
				'condition' => array( 'enable_filter' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'filter_alignment',
			array(
				'label'     => __( 'Filter Alignment', 'sew' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'sew' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'sew' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'sew' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filters' => 'justify-content: {{VALUE}};',
				),
				'condition' => array( 'enable_filter' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * CONTENT TAB — LOAD MORE
	 * ===================================================== */
	protected function register_load_more_content_controls() {
		$this->start_controls_section(
			'section_load_more_content',
			array(
				'label' => __( 'Load More', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_load_more',
			array(
				'label'   => __( 'Enable Load More', 'sew' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label'     => __( 'Button Text', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Load More', 'sew' ),
				'condition' => array( 'enable_load_more' => 'yes' ),
			)
		);

		$this->add_control(
			'load_more_loading_text',
			array(
				'label'     => __( 'Loading Text', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Loading...', 'sew' ),
				'condition' => array( 'enable_load_more' => 'yes' ),
			)
		);

		$this->add_control(
			'load_more_end_text',
			array(
				'label'     => __( 'No More Posts Text', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'No more posts', 'sew' ),
				'condition' => array( 'enable_load_more' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — CARD
	 * ===================================================== */
	protected function register_card_style_controls() {
		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => __( 'Card (Glassmorphism)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => __( 'Background Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.12)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_bg_opacity',
			array(
				'label'     => __( 'Background Opacity', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'default'   => array(
					'size' => 0.12,
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'card_backdrop_blur',
			array(
				'label'     => __( 'Backdrop Blur (px)', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array(
					'size' => 16,
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
				),
			)
		);

		$this->add_control(
			'card_border_color',
			array(
				'label'     => __( 'Border Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.25)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_width',
			array(
				'label'      => __( 'Border Width', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-card' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .sew-dpg-card',
				'fields_options' => array(
					'box_shadow' => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 20,
							'blur'       => 40,
							'spread'     => 0,
							'color'      => 'rgba(0,0,0,0.25)',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'     => __( 'Grid Gap', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'   => array(
					'size' => 24,
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — IMAGE
	 * ===================================================== */
	protected function register_image_style_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => __( 'Image', 'sew' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_image' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'     => __( 'Image Height', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
				),
				'default'   => array(
					'size' => 220,
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-card-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_object_fit',
			array(
				'label'     => __( 'Object Fit', 'sew' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => __( 'Cover', 'sew' ),
					'contain' => __( 'Contain', 'sew' ),
					'fill'    => __( 'Fill', 'sew' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-image img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — TITLE
	 * ===================================================== */
	protected function register_title_style_controls() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Title', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .sew-dpg-card-title',
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'     => __( 'Alignment', 'sew' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'sew' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'sew' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'sew' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — EXCERPT
	 * ===================================================== */
	protected function register_excerpt_style_controls() {
		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'     => __( 'Excerpt', 'sew' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.8)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .sew-dpg-card-excerpt',
			)
		);

		$this->add_control(
			'excerpt_line_height',
			array(
				'label'     => __( 'Line Height', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min'  => 1,
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-excerpt' => 'line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — META (DATE)
	 * ===================================================== */
	protected function register_meta_style_controls() {
		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'     => __( 'Meta', 'sew' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_date' => 'yes' ),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => __( 'Date Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.6)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-card-date' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .sew-dpg-card-date',
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — FILTER BUTTONS
	 * ===================================================== */
	protected function register_filter_style_controls() {
		$this->start_controls_section(
			'section_filter_style',
			array(
				'label'     => __( 'Filter Buttons', 'sew' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'enable_filter' => 'yes' ),
			)
		);

		$this->add_control(
			'filter_heading_default',
			array(
				'label' => __( 'Default State', 'sew' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'filter_bg_color',
			array(
				'label'     => __( 'Background', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.08)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_text_color',
			array(
				'label'     => __( 'Text Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_border_color',
			array(
				'label'     => __( 'Border', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.2)',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn' => 'border-color: {{VALUE}}; border-style: solid; border-width: 1px;',
				),
			)
		);

		$this->add_control(
			'filter_border_radius',
			array(
				'label'      => __( 'Border Radius', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 30,
					'right'    => 30,
					'bottom'   => 30,
					'left'     => 30,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_padding',
			array(
				'label'      => __( 'Padding', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 20,
					'bottom' => 8,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'filter_heading_active',
			array(
				'label'     => __( 'Active State', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'filter_active_bg_color',
			array(
				'label'     => __( 'Background', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn.is-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_active_text_color',
			array(
				'label'     => __( 'Text Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn.is-active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_active_border_color',
			array(
				'label'     => __( 'Border', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-filter-btn.is-active' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * STYLE TAB — BUTTONS (Read More / Load More)
	 * ===================================================== */
	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Buttons', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .sew-dpg-btn',
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-btn' => 'border-color: {{VALUE}}; border-style: solid; border-width: 1px;',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Radius', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 12,
					'right'  => 28,
					'bottom' => 12,
					'left'   => 28,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .sew-dpg-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_heading_hover',
			array(
				'label'     => __( 'Hover', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => __( 'Background', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4f46e5',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .sew-dpg-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* =====================================================
	 * HELPERS
	 * ===================================================== */

	/**
	 * Public, non-builtin-excluded post types for the Post Type control.
	 */
	protected function get_public_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$options    = array();

		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			$options[ $post_type->name ] = $post_type->label;
		}

		return $options;
	}

	/**
	 * Category options for Include/Exclude controls.
	 */
	protected function get_category_options() {
		$categories = get_categories( array( 'hide_empty' => false ) );
		$options    = array();

		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		return $options;
	}

	/**
	 * Build a WP_Query args array from either widget settings (initial
	 * render) or a sanitized AJAX request array. Shared by render()
	 * and the AJAX handlers so filtering / query rules never drift
	 * apart.
	 *
	 * @param array $settings   Elementor widget settings.
	 * @param int   $paged      Page number for load more.
	 * @param int   $category   0 for all, otherwise a term_id from the filter.
	 */
	public static function build_query_args( $settings, $paged = 1, $category = 0 ) {
		$allowed_orderby = array( 'date', 'title', 'modified', 'rand' );
		$allowed_order    = array( 'ASC', 'DESC' );

		$orderby = isset( $settings['orderby'] ) && in_array( $settings['orderby'], $allowed_orderby, true )
			? $settings['orderby']
			: 'date';

		$order = isset( $settings['order'] ) && in_array( strtoupper( $settings['order'] ), $allowed_order, true )
			? strtoupper( $settings['order'] )
			: 'DESC';

		$post_type = isset( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';
		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		$posts_per_page = isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6;
		$posts_per_page = max( 1, min( 50, $posts_per_page ) );

		$args = array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page,
			'paged'               => max( 1, absint( $paged ) ),
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => false, // Needed to know if more pages exist for Load More.
		);

		$tax_query = array();

		if ( $category > 0 ) {
			$tax_query[] = array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => array( $category ),
			);
		} elseif ( ! empty( $settings['include_categories'] ) ) {
			$include = array_map( 'absint', (array) $settings['include_categories'] );
			$include = array_filter( $include );
			if ( ! empty( $include ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $include,
				);
			}
		}

		if ( ! empty( $settings['exclude_categories'] ) ) {
			$exclude = array_map( 'absint', (array) $settings['exclude_categories'] );
			$exclude = array_filter( $exclude );
			if ( ! empty( $exclude ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $exclude,
					'operator' => 'NOT IN',
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			if ( count( $tax_query ) > 1 ) {
				$tax_query['relation'] = 'AND';
			}
			$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return $args;
	}

	/**
	 * Render a single post card. Shared by render() and the AJAX
	 * handlers so markup can never drift between initial load,
	 * filtering and load-more.
	 */
	public static function render_card( $settings ) {
		$show_image     = ! empty( $settings['show_image'] ) && 'yes' === $settings['show_image'];
		$show_excerpt   = ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'];
		$show_date      = ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'];
		$show_read_more = ! empty( $settings['show_read_more'] ) && 'yes' === $settings['show_read_more'];
		$excerpt_length = isset( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 20;
		$read_more_text = isset( $settings['read_more_text'] ) ? $settings['read_more_text'] : __( 'Read More', 'sew' );

		ob_start();
		?>
		<article <?php post_class( 'sew-dpg-card' ); ?>>
			<?php if ( $show_image && has_post_thumbnail() ) : ?>
				<div class="sew-dpg-card-image">
					<a href="<?php echo esc_url( get_permalink() ); ?>" tabindex="-1" aria-hidden="true">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'large' ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="sew-dpg-card-body">
				<?php if ( $show_date ) : ?>
					<div class="sew-dpg-card-date"><?php echo esc_html( get_the_date() ); ?></div>
				<?php endif; ?>

				<h3 class="sew-dpg-card-title">
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php echo esc_html( get_the_title() ); ?>
					</a>
				</h3>

				<?php if ( $show_excerpt ) : ?>
					<div class="sew-dpg-card-excerpt">
						<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), $excerpt_length, '&hellip;' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $show_read_more ) : ?>
					<a class="sew-dpg-btn sew-dpg-readmore" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php echo esc_html( $read_more_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the category filter bar markup.
	 */
	protected function render_filters( $settings ) {
		if ( empty( $settings['enable_filter'] ) || 'yes' !== $settings['enable_filter'] ) {
			return;
		}

		$categories = get_categories( array( 'hide_empty' => true ) );
		if ( empty( $categories ) ) {
			return;
		}

		$all_label = ! empty( $settings['filter_all_label'] ) ? $settings['filter_all_label'] : __( 'All', 'sew' );
		?>
		<div class="sew-dpg-filters" role="tablist" aria-label="<?php esc_attr_e( 'Filter posts by category', 'sew' ); ?>">
			<button type="button" class="sew-dpg-filter-btn is-active" data-category="0">
				<?php echo esc_html( $all_label ); ?>
			</button>
			<?php foreach ( $categories as $category ) : ?>
				<button type="button" class="sew-dpg-filter-btn" data-category="<?php echo esc_attr( $category->term_id ); ?>">
					<?php echo esc_html( $category->name ); ?>
				</button>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Widget frontend render.
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$widget_id  = $this->get_id();
		$query_args = self::build_query_args( $settings, 1, 0 );
		$query      = new \WP_Query( $query_args );

		$max_pages       = (int) $query->max_num_pages;
		$enable_load_more = ! empty( $settings['enable_load_more'] ) && 'yes' === $settings['enable_load_more'];

		// Settings passed to JS: only what the frontend needs, never raw query args.
		// Elementor responsive controls store desktop/tablet/mobile values
		// under these three setting keys. Pass all three values to JavaScript.
		$desktop_posts_per_page = isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6;
		$tablet_posts_per_page  = isset( $settings['posts_per_page_tablet'] ) ? absint( $settings['posts_per_page_tablet'] ) : 4;
		$mobile_posts_per_page  = isset( $settings['posts_per_page_mobile'] ) ? absint( $settings['posts_per_page_mobile'] ) : 2;

		$desktop_posts_per_page = max( 1, min( 50, $desktop_posts_per_page ) );
		$tablet_posts_per_page  = max( 1, min( 50, $tablet_posts_per_page ) );
		$mobile_posts_per_page  = max( 1, min( 50, $mobile_posts_per_page ) );

		$js_settings = array(
			'postType'            => isset( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post',
			'postsPerPage'        => $desktop_posts_per_page,
			'postsPerPageTablet'  => $tablet_posts_per_page,
			'postsPerPageMobile'  => $mobile_posts_per_page,
			'orderby'             => isset( $settings['orderby'] ) ? $settings['orderby'] : 'date',
			'order'             => isset( $settings['order'] ) ? $settings['order'] : 'DESC',
			'includeCategories' => isset( $settings['include_categories'] ) ? array_map( 'absint', (array) $settings['include_categories'] ) : array(),
			'excludeCategories' => isset( $settings['exclude_categories'] ) ? array_map( 'absint', (array) $settings['exclude_categories'] ) : array(),
			'showImage'         => ! empty( $settings['show_image'] ) && 'yes' === $settings['show_image'],
			'showExcerpt'       => ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'],
			'excerptLength'     => isset( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 20,
			'showDate'          => ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'],
			'showReadMore'      => ! empty( $settings['show_read_more'] ) && 'yes' === $settings['show_read_more'],
			'readMoreText'      => isset( $settings['read_more_text'] ) ? $settings['read_more_text'] : __( 'Read More', 'sew' ),
			'enableLoadMore'    => $enable_load_more,
			'loadMoreText'      => isset( $settings['load_more_text'] ) ? $settings['load_more_text'] : __( 'Load More', 'sew' ),
			'loadingText'       => isset( $settings['load_more_loading_text'] ) ? $settings['load_more_loading_text'] : __( 'Loading...', 'sew' ),
			'endText'           => isset( $settings['load_more_end_text'] ) ? $settings['load_more_end_text'] : __( 'No more posts', 'sew' ),
			'currentPage'       => 1,
			'maxPages'          => $max_pages,
		);
		?>
		<div class="sew-dpg-wrapper"
			id="sew-dpg-<?php echo esc_attr( $widget_id ); ?>"
			data-widget-id="<?php echo esc_attr( $widget_id ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $js_settings ) ); ?>">

			<?php $this->render_filters( $settings ); ?>

			<div class="sew-dpg-grid">
				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						echo self::render_card( $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- render_card() escapes internally.
					}
					wp_reset_postdata();
				} else {
					echo '<p class="sew-dpg-no-posts">' . esc_html__( 'No posts found.', 'sew' ) . '</p>';
				}
				?>
			</div>

			<?php if ( $enable_load_more ) : ?>
				<?php /* SEW FIX: wrapper is always printed when Load More is enabled, even
				 * if the FIRST query only has one page. Previously this block only rendered
				 * when $max_pages > 1 at initial load, so a category with a single page of
				 * posts meant the button never existed in the DOM at all -- and once a filter
				 * click swapped it out for "no more posts" text, it could never come back for
				 * a category that legitimately had more pages. JS now toggles visibility /
				 * label instead of relying on PHP to decide whether the element exists. */ ?>
				<div class="sew-dpg-load-more-wrap" <?php echo $max_pages <= 1 ? 'style="display:none;"' : ''; ?>>
					<button type="button" class="sew-dpg-btn sew-dpg-load-more">
						<?php echo esc_html( $js_settings['loadMoreText'] ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * PHP render used by Elementor's "content" preview in the editor
	 * for dynamic/AJAX widgets is unnecessary here since render()
	 * already outputs full markup for both editor and frontend.
	 */

	/* =====================================================
	 * AJAX HANDLERS
	 *
	 * Both filtering and load-more go through the same secured
	 * endpoint. Registered here (rather than in a shared Ajax
	 * class) so this feature ships as a single self-contained
	 * widget file, per the "only create the widget" requirement.
	 * ===================================================== */

	/**
	 * AJAX: sew_dpg_query
	 *
	 * Handles both category filtering and load-more pagination.
	 * Security:
	 *  - check_ajax_referer() validates the nonce created with
	 *    wp_create_nonce( 'sew_dpg_nonce' ) and localized to JS.
	 *  - Every input is sanitized (absint / sanitize_text_field).
	 *  - orderby / order are validated against a strict allowlist
	 *    inside build_query_args(), never taken raw from the request.
	 *  - Only widget settings fields we explicitly whitelist below
	 *    are pulled from the request; nothing arbitrary reaches
	 *    build_query_args().
	 *  - All output is escaped via render_card()/esc_* helpers.
	 */
	public static function ajax_query() {
		check_ajax_referer( 'sew_dpg_nonce', 'nonce' );

		$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
		$category  = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
		$page      = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$page      = max( 1, $page );

		if ( empty( $widget_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Missing widget reference.', 'sew' ) ), 400 );
		}

		// Rebuild a minimal, whitelisted settings array from the request.
		// Only known keys are read; nothing else from $_POST is trusted.
		$allowed_orderby = array( 'date', 'title', 'modified', 'rand' );
		$allowed_order    = array( 'ASC', 'DESC' );

		$post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : 'post';

		// Keep AJAX validation aligned with the Elementor Post Type control.
		$public_post_types = get_post_types( array( 'public' => true ), 'names' );
		unset( $public_post_types['attachment'] );

		if ( ! isset( $public_post_types[ $post_type ] ) ) {
			$post_type = 'post';
		}

		$orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'date';
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}

		$order = isset( $_POST['order'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_POST['order'] ) ) ) : 'DESC';
		if ( ! in_array( $order, $allowed_order, true ) ) {
			$order = 'DESC';
		}

		$posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 6;
		if ( $posts_per_page < 1 || $posts_per_page > 50 ) {
			$posts_per_page = 6;
		}

		$include_categories = array();
		if ( ! empty( $_POST['include_categories'] ) && is_array( $_POST['include_categories'] ) ) {
			$include_categories = array_map( 'absint', wp_unslash( $_POST['include_categories'] ) );
		}

		$exclude_categories = array();
		if ( ! empty( $_POST['exclude_categories'] ) && is_array( $_POST['exclude_categories'] ) ) {
			$exclude_categories = array_map( 'absint', wp_unslash( $_POST['exclude_categories'] ) );
		}

		$settings = array(
			'post_type'           => $post_type,
			'orderby'             => $orderby,
			'order'               => $order,
			'posts_per_page'      => $posts_per_page,
			'include_categories'  => $include_categories,
			'exclude_categories'  => $exclude_categories,
			'show_image'          => isset( $_POST['show_image'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['show_image'] ) ) ? 'yes' : '',
			'show_excerpt'        => isset( $_POST['show_excerpt'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['show_excerpt'] ) ) ? 'yes' : '',
			'excerpt_length'      => isset( $_POST['excerpt_length'] ) ? absint( $_POST['excerpt_length'] ) : 20,
			'show_date'           => isset( $_POST['show_date'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['show_date'] ) ) ? 'yes' : '',
			'show_read_more'      => isset( $_POST['show_read_more'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['show_read_more'] ) ) ? 'yes' : '',
			'read_more_text'      => isset( $_POST['read_more_text'] ) ? sanitize_text_field( wp_unslash( $_POST['read_more_text'] ) ) : __( 'Read More', 'sew' ),
		);

		$query_args = self::build_query_args( $settings, $page, $category );
		$query      = new \WP_Query( $query_args );

		$html = '';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$html .= self::render_card( $settings );
			}
			wp_reset_postdata();
		}

		wp_send_json_success(
			array(
				'html'      => $html,
				'has_posts' => $query->have_posts() || $query->post_count > 0,
				'max_pages' => (int) $query->max_num_pages,
				'page'      => $page,
				'found'     => (int) $query->found_posts,
			)
		);
	}
}


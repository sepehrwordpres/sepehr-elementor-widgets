<?php
namespace SEW\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pricing_Table_Pro extends Widget_Base {

	public function get_name(): string {
		return 'sew-pricing-table-pro';
	}

	public function get_title(): string {
		return esc_html__( 'Pricing Table Pro', 'sew' );
	}

	public function get_icon(): string {
		return 'eicon-price-table';
	}

	public function get_categories(): array {
		return [ 'sew-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'sew-pricing-table-pro-style' ];
	}

	protected function register_controls(): void {
		// ==========================================
		// TAB CONTENT: HEADER & PRICE
		// ==========================================
		$this->start_controls_section(
			'section_header_price',
			[
				'label' => esc_html__( 'عنوان و قیمت', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'عنوان پلن', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'پلن حرفه‌ای', 'sew' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'توضیحات کوتاه', 'sew' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'مناسب برای کسب‌وکارهای در حال رشد و تیم‌های متوسط', 'sew' ),
				'rows'    => 3,
			]
		);

		$this->add_control(
			'price',
			[
				'label'   => esc_html__( 'قیمت', 'sew' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '۴۹',
			]
		);

		$this->add_control(
			'currency',
			[
				'label'   => esc_html__( 'واحد پول', 'sew' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'هزار تومان',
			]
		);

		$this->add_control(
			'period',
			[
				'label'   => esc_html__( 'دوره قیمت‌گذاری', 'sew' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '/ ماهانه',
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB CONTENT: BADGE
		// ==========================================
		$this->start_controls_section(
			'section_badge_content',
			[
				'label' => esc_html__( 'نشان (Badge)', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label'        => esc_html__( 'فعال‌سازی نشان', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'بله', 'sew' ),
				'label_off'    => esc_html__( 'خیر', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label'     => esc_html__( 'متن نشان', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'پیشنهاد ویژه', 'sew' ),
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB CONTENT: FEATURES
		// ==========================================
		$this->start_controls_section(
			'section_features_content',
			[
				'label' => esc_html__( 'ویژگی‌ها (Features)', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'feature_text',
			[
				'label'       => esc_html__( 'متن ویژگی', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'دسترسی نامحدود به ابزارها', 'sew' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'is_active',
			[
				'label'        => esc_html__( 'فعال بودن ویژگی', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'فعال', 'sew' ),
				'label_off'    => esc_html__( 'غیرفعال', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$repeater->add_control(
			'feature_icon',
			[
				'label'   => esc_html__( 'آیکون اختیاری', 'sew' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'label'       => esc_html__( 'لیست ویژگی‌ها', 'sew' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'feature_text' => esc_html__( 'پشتیبانی ۲۴ ساعته اختصاصی', 'sew' ),
						'is_active'    => 'yes',
					],
					[
						'feature_text' => esc_html__( 'دسترسی کامل به سورس‌پک', 'sew' ),
						'is_active'    => 'yes',
					],
					[
						'feature_text' => esc_html__( 'دامنه رایگان سالانه', 'sew' ),
						'is_active'    => 'no',
					],
				],
				'title_field' => '{{{ feature_text }}}',
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB CONTENT: BUTTON
		// ==========================================
		$this->start_controls_section(
			'section_button_content',
			[
				'label' => esc_html__( 'دکمه اقدام (CTA Button)', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'        => esc_html__( 'نمایش دکمه', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'بله', 'sew' ),
				'label_off'    => esc_html__( 'خیر', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'متن دکمه', 'sew' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'شروع پلن حرفه‌ای', 'sew' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_link',
			[
				'label'       => esc_html__( 'لینک دکمه', 'sew' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'show_button' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: CARD (Glassmorphism)
		// ==========================================
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'کارت شیشه‌ای (Card)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_blur',
			[
				'label'      => esc_html__( 'میزان تاریکی شیشه (Blur px)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-card' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) saturate(180%); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}) saturate(180%);',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'label'    => esc_html__( 'حاشیه (Border)', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-card',
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'فاصله داخلی (Padding)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'label'    => esc_html__( 'سایه (Box Shadow)', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-card',
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: TITLE & DESCRIPTION
		// ==========================================
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'عنوان و توضیحات', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_alignment',
			[
				'label'     => esc_html__( 'ترازبندی', 'sew' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'right'  => [
						'title' => esc_html__( 'راست', 'sew' ),
						'icon'  => 'eicon-text-align-right',
					],
					'center' => [
						'title' => esc_html__( 'وسط', 'sew' ),
						'icon'  => 'eicon-text-align-center',
					],
					'left'   => [
						'title' => esc_html__( 'چپ', 'sew' ),
						'icon'  => 'eicon-text-align-left',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-header' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'رنگ عنوان', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'تایپوگرافی عنوان', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'فاصله عنوان (Margin)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'رنگ توضیحات', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'label'    => esc_html__( 'تایپوگرافی توضیحات', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-description',
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: PRICE
		// ==========================================
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'بخش قیمت', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'رنگ عدد قیمت', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-amount' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'label'    => esc_html__( 'تایپوگرافی عدد قیمت', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-amount',
			]
		);

		$this->add_control(
			'currency_color',
			[
				'label'     => esc_html__( 'رنگ واحد پول', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-currency' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'currency_typography',
				'label'    => esc_html__( 'تایپوگرافی واحد پول', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-currency',
			]
		);

		$this->add_control(
			'period_color',
			[
				'label'     => esc_html__( 'رنگ دوره قیمت‌گذاری', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-period' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'period_typography',
				'label'    => esc_html__( 'تایپوگرافی دوره', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-period',
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: FEATURES
		// ==========================================
		$this->start_controls_section(
			'section_style_features',
			[
				'label' => esc_html__( 'ویژگی‌ها (Features)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_text_color',
			[
				'label'     => esc_html__( 'رنگ متن ویژگی‌های فعال', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-feature-item.is-active .sew-feature-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'feature_disabled_text_color',
			[
				'label'     => esc_html__( 'رنگ متن ویژگی‌های غیرفعال', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-feature-item.is-disabled .sew-feature-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_typography',
				'label'    => esc_html__( 'تایپوگرافی متن', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-feature-text',
			]
		);

		$this->add_control(
			'feature_icon_color',
			[
				'label'     => esc_html__( 'رنگ آیکون', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-feature-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'feature_icon_size',
			[
				'label'      => esc_html__( 'اندازه آیکون', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [ 'min' => 10, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-feature-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sew-feature-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'feature_item_spacing',
			[
				'label'      => esc_html__( 'فاصله بین ویژگی‌ها (Spacing)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 40 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-feature-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: BUTTON
		// ==========================================
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'دکمه (Button)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal State Tab
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'عادی', 'sew' ),
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'رنگ متن', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'هاور', 'sew' ),
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه در هاور', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => esc_html__( 'رنگ متن در هاور', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => esc_html__( 'تایپوگرافی', 'sew' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .sew-pricing-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => esc_html__( 'حاشیه (Border)', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'فاصله داخلی (Padding)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// TAB STYLE: BADGE
		// ==========================================
		$this->start_controls_section(
			'section_style_badge',
			[
				'label'     => esc_html__( 'نشان (Badge)', 'sew' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه نشان', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'label'     => esc_html__( 'رنگ متن نشان', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-pricing-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'label'    => esc_html__( 'تایپوگرافی نشان', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-pricing-badge',
			]
		);

		$this->add_control(
			'badge_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => esc_html__( 'فاصله داخلی (Padding)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_position_top',
			[
				'label'      => esc_html__( 'موقعیت عمودی (Top)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => -30, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-badge' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_position_left',
			[
				'label'      => esc_html__( 'موقعیت افقی (Left)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => -30, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-pricing-badge' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button_link', $settings['button_link'] );
		}
		$this->add_render_attribute( 'button_link', 'class', 'sew-pricing-button' );
		?>
		<div class="sew-pricing-card">
			<?php if ( 'yes' === $settings['show_badge'] && ! empty( $settings['badge_text'] ) ) : ?>
				<div class="sew-pricing-badge">
					<?php echo esc_html( $settings['badge_text'] ); ?>
				</div>
			<?php endif; ?>

			<div class="sew-pricing-header">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<h3 class="sew-pricing-title">
						<?php echo esc_html( $settings['title'] ); ?>
					</h3>
				<?php endif; ?>

				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<p class="sew-pricing-description">
						<?php echo wp_kses_post( $settings['description'] ); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="sew-pricing-body">
				<div class="sew-pricing-price-wrapper">
					<?php if ( ! empty( $settings['price'] ) ) : ?>
						<span class="sew-pricing-amount"><?php echo esc_html( $settings['price'] ); ?></span>
					<?php endif; ?>

					<div class="sew-pricing-price-meta">
						<?php if ( ! empty( $settings['currency'] ) ) : ?>
							<span class="sew-pricing-currency"><?php echo esc_html( $settings['currency'] ); ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $settings['period'] ) ) : ?>
							<span class="sew-pricing-period"><?php echo esc_html( $settings['period'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $settings['features_list'] ) && is_array( $settings['features_list'] ) ) : ?>
					<ul class="sew-pricing-features">
						<?php foreach ( $settings['features_list'] as $item ) :
							$is_active_class = ( 'yes' === $item['is_active'] ) ? 'is-active' : 'is-disabled';
							?>
							<li class="sew-feature-item <?php echo esc_attr( $is_active_class ); ?>">
								<?php if ( ! empty( $item['feature_icon']['value'] ) ) : ?>
									<span class="sew-feature-icon">
										<?php Icons_Manager::render_icon( $item['feature_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
								<span class="sew-feature-text">
									<?php echo esc_html( $item['feature_text'] ); ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $settings['show_button'] && ! empty( $settings['button_text'] ) ) : ?>
				<div class="sew-pricing-footer">
					<a <?php $this->print_render_attribute_string( 'button_link' ); ?>>
						<?php echo esc_html( $settings['button_text'] ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
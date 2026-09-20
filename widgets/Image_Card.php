<?php
namespace SEW\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Icons_Manager;

class Image_Card extends Widget_Base {

	public function get_name(): string {
		return 'sew_image_card';
	}

	public function get_title(): string {
		return esc_html__( 'Advanced 3D Image Card', 'sew' );
	}

	public function get_icon(): string {
		return 'eicon-image-box';
	}

	public function get_categories(): array {
		return [ 'sew-widgets' ];
	}

	protected function register_controls(): void {

		// =============================================================
		// TAB CONTENT
		// =============================================================
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'محتوای کارت', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'تصویر کارت', 'sew' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__( 'زیرعنوان', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'SEPEHR',
				'label_block' => true,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'عنوان کارت', 'sew' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'این یک متن نمونه برای کارت تصویری مدرن است.',
				'rows'        => 3,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'متن دکمه', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'مشاهده بیشتر',
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_link',
			[
				'label'       => esc_html__( 'لینک دکمه', 'sew' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'default'     => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'   => esc_html__( 'آیکون دکمه', 'sew' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-arrow-left',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'موقعیت آیکون نسبت به متن', 'sew' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'قبل از متن (راست)', 'sew' ),
					'right' => esc_html__( 'بعد از متن (چپ)', 'sew' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label'     => esc_html__( 'ترازبندی کلی محتوا', 'sew' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'راست‌چین', 'sew' ),
						'icon'  => 'eicon-text-align-right',
					],
					'center'     => [
						'title' => esc_html__( 'وسط‌چین', 'sew' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'چپ‌چین', 'sew' ),
						'icon'  => 'eicon-text-align-left',
					],
				],
				'default'   => 'flex-start',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-card-body'  => 'align-items: {{VALUE}}; text-align: {{VALUE}};',
					'{{WRAPPER}} .sew-card-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 1. 3D EFFECTS & ANIMATION (FIXED SELECTORS)
		// =============================================================
		$this->start_controls_section(
			'section_style_3d',
			[
				'label' => esc_html__( 'تنظیمات افکت ۳ بعدی (3D)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'enable_3d',
			[
				'label'        => esc_html__( 'فعال‌سازی افکت ۳ بعدی', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'بله', 'sew' ),
				'label_off'    => esc_html__( 'خیر', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'perspective',
			[
				'label'      => esc_html__( 'عمق پرسپکتیو (Perspective)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 1000 ],
				'range'      => [
					'px' => [ 'min' => 300, 'max' => 2000 ],
				],
				'condition'  => [ 'enable_3d' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-3d-perspective' => 'perspective: {{SIZE}}px !important;',
				],
			]
		);

		$this->add_control(
			'rotate_x',
			[
				'label'     => esc_html__( 'زاویه چرخش عمودی (Rotate X)', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => 8 ],
				'range'     => [
					'px' => [ 'min' => -30, 'max' => 30 ],
				],
				'condition' => [ 'enable_3d' => 'yes' ],
				'selectors' => [
					'{{WRAPPER}}' => '--sew-rotate-x: {{SIZE}}deg;',
				],
			]
		);

		$this->add_control(
			'rotate_y',
			[
				'label'     => esc_html__( 'زاویه چرخش افقی (Rotate Y)', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => -8 ],
				'range'     => [
					'px' => [ 'min' => -30, 'max' => 30 ],
				],
				'condition' => [ 'enable_3d' => 'yes' ],
				'selectors' => [
					'{{WRAPPER}}' => '--sew-rotate-y: {{SIZE}}deg;',
				],
			]
		);

		$this->add_control(
			'translate_z',
			[
				'label'     => esc_html__( 'عمق شناوری محتوا (Translate Z)', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => 30 ],
				'range'     => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'condition' => [ 'enable_3d' => 'yes' ],
				'selectors' => [
					'{{WRAPPER}} .sew-image-card-wrapper:hover .sew-card-media' => 'transform: translateZ({{SIZE}}px) !important;',
					'{{WRAPPER}} .sew-image-card-wrapper:hover .sew-card-btn'   => 'transform: translateZ(calc({{SIZE}}px * 0.8)) !important;',
					'{{WRAPPER}} .sew-image-card-wrapper:hover .sew-card-title' => 'transform: translateZ(calc({{SIZE}}px * 0.5)) !important;',
				],
			]
		);

		$this->add_control(
			'glow_effect',
			[
				'label'        => esc_html__( 'افکت درخشش هاور (Glow Overlay)', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'فعال', 'sew' ),
				'label_off'    => esc_html__( 'غیرفعال', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [ 'enable_3d' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 2. CARD CONTAINER
		// =============================================================
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'استایل کلی کارت', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه کارت', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0d0d0d',
				'selectors' => [
					'{{WRAPPER}} .sew-image-card-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'label'    => esc_html__( 'بوردر کارت', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-image-card-wrapper',
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-image-card-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'label'    => esc_html__( 'سایه کارت (Box Shadow)', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-image-card-wrapper',
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'پدینگ محتوا', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 3. IMAGE
		// =============================================================
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'استایل تصویر', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'عرض تصویر', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [ 'min' => 10, 'max' => 1200 ],
					'%'  => [ 'min' => 10, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-media img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'ارتفاع تصویر', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 50, 'max' => 800 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-media img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object_fit',
			[
				'label'     => esc_html__( 'فیت شدن تصویر (Object Fit)', 'sew' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'fill'    => esc_html__( 'Fill', 'sew' ),
					'cover'   => esc_html__( 'Cover', 'sew' ),
					'contain' => esc_html__( 'Contain', 'sew' ),
					'none'    => esc_html__( 'None', 'sew' ),
				],
				'selectors' => [
					'{{WRAPPER}} .sew-card-media img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_image_effects' );

		// Normal
		$this->start_controls_tab(
			'tab_image_normal',
			[ 'label' => esc_html__( 'عادی', 'sew' ) ]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'     => esc_html__( 'شفافیت (Opacity)', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
				],
				'selectors' => [
					'{{WRAPPER}} .sew-card-media img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'image_css_filters',
				'selector' => '{{WRAPPER}} .sew-card-media img',
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_image_hover',
			[ 'label' => esc_html__( 'هاور', 'sew' ) ]
		);

		$this->add_control(
			'image_hover_opacity',
			[
				'label'     => esc_html__( 'شفافیت هاور', 'sew' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
				],
				'selectors' => [
					'{{WRAPPER}} .sew-image-card-wrapper:hover .sew-card-media img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'image_hover_css_filters',
				'selector' => '{{WRAPPER}} .sew-image-card-wrapper:hover .sew-card-media img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'label'     => esc_html__( 'بوردر تصویر', 'sew' ),
				'selector'  => '{{WRAPPER}} .sew-card-media img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'انحنای تصویر (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => esc_html__( 'فاصله تصویر (Margin)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 4. SUBTITLE & TITLE
		// =============================================================
		$this->start_controls_section(
			'section_style_typography',
			[
				'label' => esc_html__( 'استایل متون', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'رنگ زیرعنوان', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .sew-card-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .sew-card-subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'      => esc_html__( 'فاصله زیرعنوان', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'رنگ عنوان', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sew-card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .sew-card-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'فاصله عنوان', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 5. BUTTON & ICON
		// =============================================================
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'استایل دکمه و آیکون', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .sew-card-btn',
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'label'     => esc_html__( 'تنظیمات اختصاصی آیکون', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'سایز آیکون', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [ 'min' => 8, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-btn-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sew-btn-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'رنگ آیکون (عادی)', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-btn-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .sew-btn-icon svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'رنگ آیکون (هاور)', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn:hover .sew-btn-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .sew-card-btn:hover .sew-btn-icon svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => esc_html__( 'فاصله آیکون تا متن', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_button_colors',
			[
				'label'     => esc_html__( 'رنگ و بوردر دکمه', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal State
		$this->start_controls_tab(
			'tab_button_normal',
			[ 'label' => esc_html__( 'عادی', 'sew' ) ]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'رنگ متن', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ab052d',
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => esc_html__( 'بوردر دکمه', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-card-btn',
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'tab_button_hover',
			[ 'label' => esc_html__( 'هاور', 'sew' ) ]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => esc_html__( 'رنگ متن هاور', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه هاور', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'رنگ بوردر هاور', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sew-card-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'انحنای دکمه (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .sew-card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'پدینگ دکمه', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button_attr', $settings['button_link'] );
		}
		$this->add_render_attribute( 'button_attr', 'class', 'sew-card-btn' );

		$has_icon = ! empty( $settings['selected_icon']['value'] );

		$is_3d    = 'yes' === $settings['enable_3d'];
		$has_glow = $is_3d && 'yes' === $settings['glow_effect'];
		?>
		<style>
			{{WRAPPER}} {
				--sew-rotate-x: 8deg;
				--sew-rotate-y: -8deg;
			}
			.sew-3d-perspective {
				perspective: 1000px;
				width: 100%;
				transform-style: preserve-3d;
			}
			.sew-image-card-wrapper {
				position: relative;
				overflow: hidden;
				display: flex;
				flex-direction: column;
				transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.5s ease;
				transform-style: preserve-3d !important;
				border: 1px solid rgba(255, 255, 255, 0.08);
			}

			<?php if ( $is_3d ) : ?>
			.sew-image-card-wrapper:hover {
				transform: rotateX(var(--sew-rotate-x)) rotateY(var(--sew-rotate-y)) translateY(-8px) !important;
				box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(67, 172, 165, 0.2) !important;
			}
			<?php endif; ?>

			<?php if ( $has_glow ) : ?>
			.sew-image-card-wrapper::before {
				content: '';
				position: absolute;
				top: -50%;
				left: -50%;
				width: 200%;
				height: 200%;
				background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 60%);
				opacity: 0;
				transform: translateZ(1px);
				transition: opacity 0.5s ease;
				pointer-events: none;
				z-index: 5;
			}
			.sew-image-card-wrapper:hover::before {
				opacity: 1;
			}
			<?php endif; ?>

			.sew-card-media {
				overflow: hidden;
				display: flex;
				justify-content: center;
				align-items: center;
				transform-style: preserve-3d !important;
				transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
			}
			.sew-card-media img {
				width: 100%;
				height: auto;
				display: block;
				object-fit: cover;
				transition: transform 0.5s ease, filter 0.5s ease, opacity 0.5s ease;
			}
			.sew-card-body {
				padding: 20px;
				display: flex;
				flex-direction: column;
				align-items: flex-start;
				transform-style: preserve-3d !important;
				z-index: 2;
			}
			.sew-card-subtitle {
				font-size: 12px;
				text-transform: uppercase;
				letter-spacing: 1.5px;
				color: #888888;
				margin-bottom: 8px;
				display: block;
				transition: transform 0.5s ease;
			}
			.sew-card-title {
				font-size: 16px;
				line-height: 1.6;
				color: #ffffff;
				margin-bottom: 20px;
				transition: transform 0.5s ease;
			}
			.sew-card-btn {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				padding: 10px 22px;
				border-radius: 20px;
				background-color: #ab052d;
				color: #ffffff;
				text-decoration: none;
				font-size: 14px;
				font-weight: 500;
				gap: 8px;
				transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), background-color 0.3s ease, color 0.3s ease;
				border: 1px solid transparent;
				cursor: pointer;
				line-height: 1;
				transform-style: preserve-3d !important;
			}
			.sew-btn-icon {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				line-height: 1;
				transition: all 0.3s ease;
			}
			.sew-btn-icon svg {
				width: 1em;
				height: 1em;
				fill: currentColor;
			}
		</style>

		<div class="sew-3d-perspective">
			<div class="sew-image-card-wrapper">
				<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
					<div class="sew-card-media">
						<img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
					</div>
				<?php endif; ?>

				<div class="sew-card-body">
					<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
						<span class="sew-card-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $settings['title'] ) ) : ?>
						<h4 class="sew-card-title"><?php echo esc_html( $settings['title'] ); ?></h4>
					<?php endif; ?>

					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<a <?php $this->print_render_attribute_string( 'button_attr' ); ?>>
							<?php if ( $has_icon && 'left' === $settings['icon_align'] ) : ?>
								<span class="sew-btn-icon"><?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
							<?php endif; ?>

							<span class="sew-btn-text"><?php echo esc_html( $settings['button_text'] ); ?></span>

							<?php if ( $has_icon && 'right' === $settings['icon_align'] ) : ?>
								<span class="sew-btn-icon"><?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
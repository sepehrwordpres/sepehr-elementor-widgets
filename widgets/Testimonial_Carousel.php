<?php
namespace SEW\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

class Testimonial_Carousel_Pro extends Widget_Base {

	public function get_name(): string {
		return 'sew-testimonial-carousel-pro';
	}

	public function get_title(): string {
		return esc_html__( 'Testimonial Carousel Pro', 'sew' );
	}

	public function get_icon(): string {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories(): array {
		return [ 'sew-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'swiper', 'sew-testimonial-carousel-pro-style' ];
	}

	public function get_script_depends(): array {
		return [ 'swiper', 'sew-testimonial-carousel-pro-script' ];
	}

	protected function register_controls(): void {

		// =============================================================
		// TAB CONTENT: REPEATER ITEMS
		// =============================================================
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'نظرات مشتریان', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			[
				'label'       => esc_html__( 'نام مشتری', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'علی محمدی',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'role',
			[
				'label'       => esc_html__( 'سمت / شغل', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'مدیر محصول',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'company',
			[
				'label'       => esc_html__( 'نام شرکت', 'sew' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'شرکت سپهر',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'testimonial_text',
			[
				'label'       => esc_html__( 'متن نظر', 'sew' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است.',
				'rows'        => 4,
			]
		);

		$repeater->add_control(
			'avatar',
			[
				'label'   => esc_html__( 'تصویر آواتار', 'sew' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label'   => esc_html__( 'امتیاز (۱ تا ۵)', 'sew' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 5,
				'step'    => 0.5,
				'default' => 5,
			]
		);

		$repeater->add_control(
			'profile_link',
			[
				'label'       => esc_html__( 'لینک پروفایل (اختیاری)', 'sew' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://linkedin.com/in/user',
			]
		);

		$this->add_control(
			'testimonials',
			[
				'label'       => esc_html__( 'لیست نظرات', 'sew' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'name'             => 'رضا احمدی',
						'role'             => 'توسعه‌دهنده وب',
						'company'          => 'آرین نت',
						'testimonial_text' => 'کیفیت ساخت و پشتیبانی فوق‌العاده عالی است. از خریدم بسیار راضی هستم.',
						'rating'           => 5,
					],
					[
						'name'             => 'سارا کاظمی',
						'role'             => 'طراح UI/UX',
						'company'          => 'استودیو طراحی',
						'testimonial_text' => 'ارگونومی و طراحی شیشه‌ای (Glassmorphism) این ویجت جلوه بی‌نظیری به سایت داده است.',
						'rating'           => 5,
					],
				],
				'title_field' => '{{{ name }}} - {{{ company }}}',
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB CONTENT: CAROUSEL SETTINGS
		// =============================================================
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'تنظیمات اسلایدر', 'sew' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'تعداد اسلاید در صفحه', 'sew' ),
				'type'           => Controls_Manager::NUMBER,
				'desktop_default' => 3,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'min'            => 1,
				'max'            => 6,
			]
		);

		$this->add_responsive_control(
			'slides_space_between',
			[
				'label'          => esc_html__( 'فاصله بین اسلایدها (px)', 'sew' ),
				'type'           => Controls_Manager::NUMBER,
				'desktop_default' => 20,
				'tablet_default'  => 15,
				'mobile_default'  => 10,
				'min'            => 0,
				'max'            => 50,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'پخش خودکار (Autoplay)', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'فعال', 'sew' ),
				'label_off'    => esc_html__( 'غیرفعال', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'سرعت پخش خودکار (میلی‌ثانیه)', 'sew' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
				'condition' => [ 'autoplay' => 'yes' ],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => esc_html__( 'تکرار مداوم (Loop)', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'فعال', 'sew' ),
				'label_off'    => esc_html__( 'غیرفعال', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label'        => esc_html__( 'نمایش دکمه‌های قبلی/بعدی', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'بله', 'sew' ),
				'label_off'    => esc_html__( 'خیر', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'        => esc_html__( 'نمایش نقاط صفحه (Pagination)', 'sew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'بله', 'sew' ),
				'label_off'    => esc_html__( 'خیر', 'sew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 1. GLASS CARD STYLE
		// =============================================================
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'کارت Glassmorphism', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'رنگ پس‌زمینه کارت', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.05)',
				'selectors' => [
					'{{WRAPPER}} .sew-glass-card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'backdrop_blur',
			[
				'label'      => esc_html__( 'میزان تاریکی پس‌زمینه (Blur)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 30 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-glass-card' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'label'    => esc_html__( 'بوردر کارت', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-glass-card',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-glass-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'label'    => esc_html__( 'سایه کارت (Box Shadow)', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-glass-card',
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'پدینگ کارت', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-glass-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 2. AVATAR
		// =============================================================
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'استایل تصویر / آواتار', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label'      => esc_html__( 'سایز تصویر (عرض و ارتفاع)', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 30, 'max' => 150 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-testimonial-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'avatar_border',
				'label'    => esc_html__( 'بوردر تصویر', 'sew' ),
				'selector' => '{{WRAPPER}} .sew-testimonial-avatar img',
			]
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			[
				'label'      => esc_html__( 'انحنای زوایا (Border Radius)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-testimonial-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 3. TYPOGRAPHY & TEXTS
		// =============================================================
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'متون و تایپوگرافی', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Name
		$this->add_control(
			'heading_name',
			[
				'label' => esc_html__( 'نام مشتری', 'sew' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'رنگ متن', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .sew-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .sew-testimonial-name',
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label'      => esc_html__( 'فاصله (Margin)', 'sew' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .sew-testimonial-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Role & Company
		$this->add_control(
			'heading_role',
			[
				'label'     => esc_html__( 'سمت و شرکت', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'role_color',
			[
				'label'     => esc_html__( 'رنگ سمت / شرکت', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a1a1aa',
				'selectors' => [
					'{{WRAPPER}} .sew-testimonial-role' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'role_typography',
				'selector' => '{{WRAPPER}} .sew-testimonial-role',
			]
		);

		// Testimonial Text
		$this->add_control(
			'heading_text',
			[
				'label'     => esc_html__( 'متن نظر', 'sew' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'ترازبندی متن', 'sew' ),
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
				'default'   => 'right',
				'selectors' => [
					'{{WRAPPER}} .sew-glass-card' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'رنگ متن', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e4e4e7',
				'selectors' => [
					'{{WRAPPER}} .sew-testimonial-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .sew-testimonial-text',
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 4. RATING STARS
		// =============================================================
		$this->start_controls_section(
			'section_style_rating',
			[
				'label' => esc_html__( 'امتیاز / ستاره‌ها', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'star_color',
			[
				'label'     => esc_html__( 'رنگ ستاره پر', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => [
					'{{WRAPPER}} .sew-star-filled' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label'     => esc_html__( 'رنگ ستاره خالی', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3f3f46',
				'selectors' => [
					'{{WRAPPER}} .sew-star-empty' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'star_size',
			[
				'label'      => esc_html__( 'اندازه ستاره', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 10, 'max' => 40 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-testimonial-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'star_spacing',
			[
				'label'      => esc_html__( 'فاصله بین ستاره‌ها', 'sew' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 20 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .sew-testimonial-rating' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// =============================================================
		// TAB STYLE: 5. NAVIGATION & PAGINATION
		// =============================================================
		$this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'دکمه‌ها و نقاط (Navigation)', 'sew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'رنگ فلش', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .sew-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color',
			[
				'label'     => esc_html__( 'پس‌زمینه فلش', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.1)',
				'selectors' => [
					'{{WRAPPER}} .sew-swiper-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dot_color',
			[
				'label'     => esc_html__( 'رنگ نقاط عادی', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.3)',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_dot_color',
			[
				'label'     => esc_html__( 'رنگ نقطه فعال', 'sew' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['testimonials'] ) ) {
			return;
		}

		$swiper_settings = [
			'slidesPerView' => [
				'desktop' => (int) ( $settings['slides_to_show'] ?? 3 ),
				'tablet'  => (int) ( $settings['slides_to_show_tablet'] ?? 2 ),
				'mobile'  => (int) ( $settings['slides_to_show_mobile'] ?? 1 ),
			],
			'spaceBetween'  => [
				'desktop' => (int) ( $settings['slides_space_between'] ?? 20 ),
				'tablet'  => (int) ( $settings['slides_space_between_tablet'] ?? 15 ),
				'mobile'  => (int) ( $settings['slides_space_between_mobile'] ?? 10 ),
			],
			'autoplay'      => 'yes' === $settings['autoplay'],
			'autoplaySpeed' => (int) ( $settings['autoplay_speed'] ?? 3000 ),
			'loop'          => 'yes' === $settings['loop'],
		];

		$this->add_render_attribute( 'wrapper', 'class', 'sew-testimonial-carousel-wrapper elementor-swiper' );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="sew-testimonial-carousel-container swiper-container swiper" data-settings='<?php echo esc_attr( wp_json_encode( $swiper_settings ) ); ?>'>
				<div class="swiper-wrapper">
					<?php foreach ( $settings['testimonials'] as $index => $item ) : ?>
						<div class="swiper-slide">
							<div class="sew-glass-card">
								
								<div class="sew-testimonial-rating">
									<?php
									$rating = (float) ( $item['rating'] ?? 5 );
									for ( $i = 1; $i <= 5; $i++ ) {
										if ( $rating >= $i ) {
											echo '<span class="sew-star-filled">&#9733;</span>';
										} else {
											echo '<span class="sew-star-empty">&#9734;</span>';
										}
									}
									?>
								</div>

								<div class="sew-testimonial-text">
									<?php echo wp_kses_post( $item['testimonial_text'] ); ?>
								</div>

								<div class="sew-testimonial-author">
									<?php if ( ! empty( $item['avatar']['url'] ) ) : ?>
										<div class="sew-testimonial-avatar">
											<img src="<?php echo esc_url( $item['avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
										</div>
									<?php endif; ?>

									<div class="sew-testimonial-info">
										<?php if ( ! empty( $item['profile_link']['url'] ) ) : ?>
											<a href="<?php echo esc_url( $item['profile_link']['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="sew-testimonial-name">
												<?php echo esc_html( $item['name'] ); ?>
											</a>
										<?php else : ?>
											<span class="sew-testimonial-name"><?php echo esc_html( $item['name'] ); ?></span>
										<?php endif; ?>

										<div class="sew-testimonial-role">
											<?php echo esc_html( $item['role'] ); ?>
											<?php if ( ! empty( $item['company'] ) ) : ?>
												<span class="sew-testimonial-company"> - <?php echo esc_html( $item['company'] ); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div>

							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $settings['show_pagination'] ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $settings['show_navigation'] ) : ?>
				<div class="sew-swiper-button sew-swiper-button-next" role="button">&#10095;</div>
				<div class="sew-swiper-button sew-swiper-button-prev" role="button">&#10094;</div>
			<?php endif; ?>
		</div>

		<?php
	}
}
<?php

namespace Motors_Elementor_Widgets_Free\Widgets;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class InventorySearchResults extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}
	}

	public function get_script_depends() {
		return array(
			$this->get_name(),
			'addtoany-core',
		);
	}

	public function get_style_depends(): array {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = self::get_name() . '-rtl';

		return $widget_styles;
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-inventory-search-results';
	}

	public function get_title() {
		return esc_html__( 'Search Result', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-inventory-search';
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'isr_content', __( 'List View', 'stm_vehicles_listing' ) );

		if ( stm_is_multilisting() ) {

			$this->add_control(
				'post_type',
				array(
					'label'   => __( 'Listing Type', 'stm_vehicles_listing' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => Helper::stm_ew_multi_listing_types(),
					'default' => 'listings',
				),
			);

		}

		$this->add_control(
			'ppp_on_list',
			array(
				'label'   => __( 'Listings per Page', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => '10',
			)
		);

		$this->add_control(
			'list_thumb_img_size',
			array(
				'label'   => __( 'Image Size', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => Helper::stm_ew_get_image_sizes( true, true, true ),
			),
		);

		$this->stm_end_control_section();

		$this->stm_start_content_controls_section( 'isr_content_grid', __( 'Grid View', 'stm_vehicles_listing' ) );

		$this->add_control(
			'ppp_on_grid',
			array(
				'label'   => __( 'Listings per Page', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => '9',
			)
		);

		$this->add_control(
			'quant_grid_items',
			array(
				'label'       => __( 'Number of Listings per Row', 'stm_vehicles_listing' ),
				'description' => __( 'Reload the page to apply the settings.', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => array(
					'2' => '2',
					'3' => '3',
				),
				'default'     => '3',
			)
		);

		$this->add_control(
			'grid_thumb_img_size',
			array(
				'label'   => __( 'Image Size', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => Helper::stm_ew_get_image_sizes( true, true, true ),
			),
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isr_featured_styles', __( 'Featured Listings Block', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isr_grid_card_settings_heading',
			array(
				'label' => __( 'Featured', 'stm_vehicle_listings' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isr_grid_card_settings_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isr_grid_card_settings_featured_title_typography',
				'label'    => __( 'Text Style', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title .heading-font',
				'exclude'  => array( 'font_size' ),
			)
		);

		$this->add_control(
			'isr_grid_card_settings_featured_title_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title .heading-font' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'isr_grid_card_settings_featured_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title .heading-font' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title .heading-font:after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'isr_grid_card_settings_featured_link_divider',
			array(
				'type'      => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isr_grid_card_settings_featured_link_heading',
			array(
				'label' => __( 'Show All Link', 'stm_vehicle_listings' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isr_grid_card_settings_featured_link_typography',
				'label'    => __( 'Text Style', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title a',
			)
		);

		$this->add_control(
			'isr_grid_card_settings_featured_link_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .stm-featured-top-cars-title a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->stm_end_control_section();

		apply_filters( 'grid_listing_card_controls', $this, self::get_name() );

		apply_filters( 'list_listing_card_controls', $this, self::get_name() );

		$this->stm_start_style_controls_section( 'isr_content_pagination', __( 'Pagination', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isr_pagination_item_typography',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a, {{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > span',
			)
		);

		$this->stm_start_ctrl_tabs( 'pagination_style' );

		$this->stm_start_ctrl_tab(
			'pagination_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'isr_pagination_item_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isr_pagination_item_bg',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isr_pagination_item_border',
				'label'    => __( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_control(
			'isr_pagination_item_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isr_pagination_item_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isr_pagination_item_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li > a',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'pagination_active',
			array(
				'label' => __( 'Active', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'isr_pagination_active_item_color_active',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isr_pagination_active_item_bg',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isr_pagination_item_border_active',
				'label'    => __( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_control(
			'isr_pagination_item_border_color_active',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isr_pagination_item_border_radius_active',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isr_pagination_item_box_shadow_active',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result ul.page-numbers li .current',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->stm_end_control_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/inventory-search-results', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {

	}
}

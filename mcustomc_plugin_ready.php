<?php
namespace MouseCustomCursor;

use MouseCustomCursor\PageSettings\Page_Settings;
// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
define( "MCUSTOMC_ASFSK_ASSETS_ADMIN_DIR_FILE", plugin_dir_url( __FILE__ ) . "assets/admin" );
class MouseCustomCursor {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function mcustomc_admin_editor_scripts() {
		add_filter( 'script_loader_tag', [ $this, 'mcustomc_admin_editor_scripts_as_a_module' ], 10, 2 );
	}

	public function mcustomc_admin_editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'mcustomc_meet_the_team_editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}
		return $tag;
	}

	public function mcustomc_all_assets_for_the_public(){
		wp_enqueue_style( 'mcustomc-customcursors-style-8cursor', plugin_dir_url( __FILE__ ) . 'assets/public/css/frontend.css', null, '1.0', 'all' );
		wp_enqueue_script( 'mcustomc-customcursors-the-cursor', plugin_dir_url( __FILE__ ) . 'assets/public/js/mcustomc-custom-cursor.js', array('jquery'), '1.0', true );
	}
	public function mcustomc_all_assets_for_elementor_editor_admin(){
		$all_css_js_file = array(
			'mcustomc-products-admin-icon' => array('mcustomc_path_admin_define'=>MCUSTOMC_ASFSK_ASSETS_ADMIN_DIR_FILE . '/icon.css'),
		);
		foreach($all_css_js_file as $handle => $fileinfo){
			wp_enqueue_style( $handle, $fileinfo['mcustomc_path_admin_define'], null, '1.0', 'all');
		}
	}

	public function mcustomc_its_after_section($section, $section_id){
		if ( 'section_advanced' === $section_id || '_section_style' === $section_id ) {

			$section->start_controls_section(
				'mcustomc_control_section',
				[
					'label' => esc_html__( 'Mouse Custom Cursor', 'mouse-custom-cursor' ),
					'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);
			$element_type = $section->get_type();
	
			$section->add_control(
				'mcustomc_custom_cursor_enable',
				array(
					'label'        => esc_html__( 'Custom Cursor', 'mouse-custom-cursor' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => esc_html__( 'Yes', 'mouse-custom-cursor' ),
					'label_off'    => esc_html__( 'No', 'mouse-custom-cursor' ),
					'return_value' => 'yes',
					'separator'    => 'before',
					'frontend_available' => true,
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_target',
				array(
					'label'     => esc_html__( 'Apply On', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'container',
					'options'   => array(
						'container'    => ucfirst( $element_type ),
						'css-selector' => esc_html__( 'Element Class/ID', 'mouse-custom-cursor' ),
					),
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_css_selector',
				array(
					'label'     => esc_html__( 'CSS Selector', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::TEXT,
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_target' => 'css-selector',
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_type',
				array(
					'label'     => esc_html__( 'Cursor Type', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'follow-text',
					'options'   => array(
						'follow-text'  => esc_html__( 'Text Type', 'mouse-custom-cursor' ),
						'follow-image' => esc_html__( 'Image Type', 'mouse-custom-cursor' ),
						'mcustomc_cursor_icon'  => esc_html__( 'Cursor Icon', 'mouse-custom-cursor' ),
					),
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_icon',
				array(
					'label'     => esc_html__( 'Choose Cursor Image/Icon', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::MEDIA,
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => 'follow-image',
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_text',
				array(
					'label'     => esc_html__( 'Cursor Text', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::TEXT,
					'default'		=> esc_html__('Cursor', 'mouse-custom-cursor'),
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => 'follow-text',
					),
				)
			);
			$section->add_control(
				'mcustomc_custom_cursor_text2',
				array(
					'label'     => esc_html__( 'Cursor Icon', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::TEXT,
					'default'		=> 'fas fa-hand-point-up',
					'description' => esc_html__('Note:- Use icon name only. (fas fa-hand-point-up)', 'mouse-custom-cursor'),
					'frontend_available' => true,
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => 'mcustomc_cursor_icon',
					),
				)
			);
	
			$section->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'mcustomc_custom_cursor_text_typography',
					'label'     => esc_html__( 'Typography', 'mouse-custom-cursor' ),
					'selector'  => '{{WRAPPER}} .mcustomc-cursor-pointer-text',
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_text_color',
				array(
					'label'     => esc_html__( 'Color', 'mouse-custom-cursor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mcustomc-cursor-pointer-text' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				)
			);
	
			$section->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'mcustomc_custom_cursor_text_bg',
					'label'     => esc_html__( 'Background', 'mouse-custom-cursor' ),
					'types'     => [ 'classic', 'gradient' ],
					'exclude'   => array( 'image' ),
					'selector'  => '{{WRAPPER}} .mcustomc-cursor-pointer-text',
					'condition' => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				]
			);
	
			$section->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'mcustomc_custom_cursor_text_border',
					'label'       => esc_html__( 'Border', 'mouse-custom-cursor' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .mcustomc-cursor-pointer-text',
					'condition'   => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				)
			);
	
			$section->add_control(
				'mcustomc_custom_cursor_text_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'mouse-custom-cursor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mcustomc-cursor-pointer-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				)
			);
	
			$section->add_responsive_control(
				'mcustomc_custom_cursor_text_padding',
				array(
					'label'      => esc_html__( 'Padding', 'mouse-custom-cursor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mcustomc-cursor-pointer-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'mcustomc_custom_cursor_enable' => 'yes',
						'mcustomc_custom_cursor_type'   => ['follow-text', 'mcustomc_cursor_icon'],
					),
				)
			);
			$section->end_controls_section();
		}
	}
	public function mcustomc_its_render($section){
		$settings      = $section->get_settings_for_display();
		$cursor_url    = $settings['mcustomc_custom_cursor_icon'];
		$cursor_text   = $settings['mcustomc_custom_cursor_text'];
		$cursor_text2   = $settings['mcustomc_custom_cursor_text2'];
		$cursor_target = $settings['mcustomc_custom_cursor_target'];
		$css_selector  = $settings['mcustomc_custom_cursor_css_selector'];

		if ( 'yes' === $settings['mcustomc_custom_cursor_enable'] ) {
			if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				wp_enqueue_script( 'powerpack-frontend' );
				wp_enqueue_script( 'mcustomc-custom-cursor' );
			}

			$custom_cursor_options = [
				'type' => $settings['mcustomc_custom_cursor_type'],
			];

			if ( ! empty( $cursor_url ) ) {
				$custom_cursor_options['url'] = $cursor_url['url'];
			}

			if ( $cursor_text ) {
				$custom_cursor_options['text'] = $cursor_text;
			}

			if ( $cursor_text2 ) {
				$custom_cursor_options['text'] = $cursor_text2;
			}

			if ( 'css-selector' === $cursor_target && $css_selector ) {
				$custom_cursor_options['target'] = 'selector';
				$custom_cursor_options['css_selector'] = $css_selector;
			}

			$section->add_render_attribute(
				'_wrapper', [
					'class'               => [ 'mcustomc-custom-cursor', 'mcustomc-custom-cursor-' . $section->get_id() ],
					'data-cursor-options' => wp_json_encode( $custom_cursor_options ),
				]
			);
		}
	}

	public function __construct() {
		// This is for register the controls
		add_action( 'elementor/element/after_section_end', [$this, 'mcustomc_its_after_section'], 10, 2 );
		// Render this controls
		add_action( 'elementor/frontend/before_render', [$this, 'mcustomc_its_render'], 10, 2 );
		// For public assets
		add_action('wp_enqueue_scripts', [$this, 'mcustomc_all_assets_for_the_public']);
		// For Elementor Editor
		add_action('elementor/editor/before_enqueue_scripts', [$this, 'mcustomc_all_assets_for_elementor_editor_admin']);
		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'mcustomc_admin_editor_scripts' ] );
	}
}

// Instantiate Plugin Class
MouseCustomCursor::instance();


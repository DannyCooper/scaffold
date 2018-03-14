<?php
/**
 * This class acts as a helper when utilizing the WordPress Customize API.
 *
 * @link       https://developer.wordpress.org/themes/customize-api/
 *
 * @package    customizer-helper
 * @copyright  Copyright (c) 2018, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @version    1.0.0
 */

/**
 * Main Customizer_Helper Class.
 */
class Customizer_Helper {

	/**
	 * The one instance of Customizer_Library.
	 *
	 * @since 1.0.0
	 *
	 * @var Customizer_Helper The one instance for the singleton.
	 */
	private static $instance;

	/**
	 * The array for storing $settings.
	 *
	 * @since 1.0.0
	 *
	 * @var array Holds the settings array.
	 */
	public $settings = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return Customizer_Helper
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
	}

	/**
	 * Instantiate or return the one Customizer_Helper instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Customizer_Helper
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add settings to the $settings property.
	 *
	 * @param array $settings The settings to be registered.
	 */
	public function add_settings( $settings = array() ) {
		$this->settings = array_merge( $settings, $this->settings );
	}

	/**
	 * Configure settings and controls for the theme customizer
	 *
	 * @since 1.0.0
	 *
	 * @param object $wp_customize The global customizer object.
	 *
	 * @return void
	 */
	public function register_settings( $wp_customize ) {

		$customizer_library = Customizer_Helper::Instance();

		// Bail early if we don't have any settings.
		if ( empty( $this->settings ) ) {
			return;
		}

		// Add the panels.
		if ( isset( $this->settings['panels'] ) ) {
			$this->add_panels( $this->settings['panels'], $wp_customize );
		}

		// Add the sections.
		if ( isset( $this->settings['sections'] ) ) {
			$this->add_sections( $this->settings['sections'], $wp_customize );
		}

		// Sets the priority for each control added.
		$loop = 0;

		// Loops through each of the settings.
		foreach ( $this->settings as $setting ) {

			// Set blank description if one isn't set.
			if ( ! isset( $setting['description'] ) ) {
				$setting['description'] = '';
			}

			if ( isset( $setting['type'] ) ) {

				$loop++;

				// Apply a default sanitization if one isn't set.
				if ( ! isset( $setting['sanitize_callback'] ) ) {
					$setting['sanitize_callback'] = $this->get_sanitization( $setting['type'] );
				}

				// Set blank active_callback if one isn't set.
				if ( ! isset( $setting['active_callback'] ) ) {
					$setting['active_callback'] = '';
				}

				// Add the setting.
				$this->add_setting( $setting, $wp_customize );

				// Priority for control.
				if ( ! isset( $setting['priority'] ) ) {
					$setting['priority'] = $loop;
				}

				// Adds control based on control type.
				switch ( $setting['type'] ) {

					case 'text':
					case 'url':
					case 'select':
					case 'radio':
					case 'checkbox':
					case 'number':
					case 'range':

						$wp_customize->add_control(
							$setting['id'], $setting
						);

						break;

					case 'color':

						$wp_customize->add_control(
							new WP_Customize_Color_Control(
								$wp_customize, $setting['id'], $setting
							)
						);

						break;

					case 'image':

						$wp_customize->add_control(
							new WP_Customize_Image_Control(
								$wp_customize,
								$setting['id'], array(
									'label'             => $setting['label'],
									'section'           => $setting['section'],
									'sanitize_callback' => $setting['sanitize_callback'],
									'priority'          => $setting['priority'],
									'active_callback'   => $setting['active_callback'],
									'description'       => $setting['description'],
								)
							)
						);

						break;

					case 'upload':

						$wp_customize->add_control(
							new WP_Customize_Upload_Control(
								$wp_customize,
								$setting['id'], array(
									'label'             => $setting['label'],
									'section'           => $setting['section'],
									'sanitize_callback' => $setting['sanitize_callback'],
									'priority'          => $setting['priority'],
									'active_callback'   => $setting['active_callback'],
									'description'       => $setting['description'],
								)
							)
						);

						break;

					case 'textarea':

							$wp_customize->add_control( 'setting_id', array(
								$wp_customize->add_control(
									$setting['id'], $setting
								),
							) );

						break;

				}
			}
		}
	}

	/**
	 * Add the customizer panels
	 *
	 * @since  1.0.0
	 *
	 * @param array $panels Panels to be added to the customizer.
	 * @param array $wp_customize WP_Customize object.
	 *
	 * @return void
	 */
	public function add_panels( $panels, $wp_customize ) {

		foreach ( $panels as $panel ) {

			if ( ! isset( $panel['description'] ) ) {
				$panel['description'] = false;
			}

			$wp_customize->add_panel( $panel['id'], $panel );
		}

	}

	/**
	 * Add the customizer sections
	 *
	 * @since 1.0.0
	 *
	 * @param array $sections Sections to be added to the customizer.
	 * @param array $wp_customize WP_Customize object.
	 *
	 * @return void
	 */
	public function add_sections( $sections, $wp_customize ) {

		foreach ( $sections as $section ) {

			if ( ! isset( $section['description'] ) ) {
				$section['description'] = false;
			}

			$wp_customize->add_section( $section['id'], $section );
		}

	}

	/**
	 * Add the setting and proper sanitization
	 *
	 * @since  1.0.0
	 *
	 * @param array $setting Setting to be added to the customizer.
	 * @param array $wp_customize WP_Customize object.
	 *
	 * @return void
	 */
	public function add_setting( $setting, $wp_customize ) {

		$settings_default = array(
			'option_type'          => 'theme_mod', // Type of the setting. Default 'theme_mod'.
			'capability'           => 'edit_theme_options', // Capability required for the setting. Default 'edit_theme_options'
			'theme_supports'       => null, // Theme features required to support the panel. Default is none.
			'default'              => null, // Default value for the setting. Default is empty string.
			'transport'            => 'refresh', // 'refresh' or 'postMessage'. Default is 'refresh'.
			'validate_callback'    => null, // Server-side validation callback for the setting's value.
			'sanitize_callback'    => 'wp_kses_post', // Callback to filter a Customize setting value in un-slashed form.
			'sanitize_js_callback' => null, // Callback to convert a Customize PHP setting value to a value that is JSON serializable.
		);

		// Settings defaults.
		$settings = array_merge( $settings_default, $setting );

		// Arguments for $wp_customize->add_setting.
		$wp_customize->add_setting( $setting['id'], array(
			'type'                 => $settings['option_type'],
			'capability'           => $settings['capability'],
			'theme_supports'       => $settings['theme_supports'],
			'default'              => $settings['default'],
			'transport'            => $settings['transport'],
			'validate_callback'    => $settings['validate_callback'],
			'sanitize_callback'    => $settings['sanitize_callback'],
			'sanitize_js_callback' => $settings['sanitize_js_callback'],
		) );

	}

	/**
	 * Get default sanitization function for setting type
	 *
	 * @since 1.0.0
	 *
	 * @param array $type The type of field to be sanitized.
	 *
	 * @return string|false The sanitization function or false.
	 */
	public function get_sanitization( $type ) {

		if ( 'text' === $type ) {
			return 'sanitize_text_field';
		}

		if ( 'textarea' === $type ) {
			return 'sanitize_textarea_field';
		}

		if ( 'color' === $type ) {
			return 'sanitize_hex_color';
		}

		if ( 'url' === $type ) {
			return 'esc_url_raw';
		}

		if ( 'select' === $type ) {
			return 'sanitize_key';
		}

		if ( 'radio' === $type ) {
			return 'sanitize_key';
		}

		if ( 'checkbox' === $type ) {
			return 'wp_validate_boolean';
		}

		if ( 'range' === $type ) {
			return 'absint';
		}

		if ( 'image' === $type ) {
			return 'esc_url_raw';
		}

		if ( 'upload' === $type ) {
			return 'esc_url_raw';
		}

		// If a custom setting is being used, return false.
		return false;
	}

}

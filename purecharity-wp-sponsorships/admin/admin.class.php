<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and all admin functions.
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/admin
 * @author     Rafael Dalprá <rafael.dalpra@toptal.com>
 */
class Purecharity_Wp_Sponsorships_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Add the Plugin Settings Menu.
	 *
	 * @since    1.0.0
	 */
	function add_admin_menu(  ) { 
		add_options_page( 'PureCharity&#8482; Sponsorships Settings', 'PureCharity&#8482; Sponsorships', 'manage_options', 'purecharity_sponsorships', array('Purecharity_Wp_Sponsorships_Admin', 'options_page') );
	}

	/**
	 * Checks for the existence of the settings.
	 *
	 * @since    1.0.0
	 */
	public static function settings_exist(  ) { 
		if( false == get_option( 'purecharity_sponsorships_settings' ) ) { 
			add_option( 'purecharity_sponsorships_settings' );
		}
	}

	/**
	 * Initializes the settings page options.
	 *
	 * @since    1.0.0
	 */
	public static function settings_init(  ) 
	{
		register_setting( 'psPluginPage', 'purecharity_sponsorships_settings' );

		add_settings_section(
			'purecharity_sponsorships_psPluginPage_section', 
			__( 'General settings', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'settings_section_callback'),
			'psPluginPage'
		);

		add_settings_field( 
			'plugin_color', 
			__( 'Main Theme Color', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'main_color_render'), 
			'psPluginPage', 
			'purecharity_sponsorships_psPluginPage_section' 
		);

		add_settings_field( 
			'plugin_style', 
			__( 'Style to Use', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'plugin_style_render'), 
			'psPluginPage', 
			'purecharity_sponsorships_psPluginPage_section' 
		);

		add_settings_field( 
			'branding_image', 
			__( 'Single Sponsor Branding Image', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'branding_image_render'), 
			'psPluginPage', 
			'purecharity_sponsorships_psPluginPage_section' 
		);

		add_settings_field( 
			'single_view_template', __( 'Single view template', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'single_view_template_render'), 
			'psPluginPage', 
			'purecharity_sponsorships_psPluginPage_section' 
		);

		add_settings_section(
			'purecharity_sponsorships_display_psPluginPage_section', 
			__( 'Display settings', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'display_settings_section_callback'),
			'psPluginPage'
		);

		add_settings_field( 
			'hide_powered_by', __( 'Hide Powered By Image', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'hide_powered_by_render'), 
			'psPluginPage', 'purecharity_sponsorships_display_psPluginPage_section' 
		);

		add_settings_field( 
			'age_filter', __( 'Display Age Filter', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'age_filter_render'), 
			'psPluginPage', 'purecharity_sponsorships_display_psPluginPage_section' 
		);

		add_settings_field( 
			'gender_filter', __( 'Display Gender Filter', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'gender_filter_render'), 
			'psPluginPage', 'purecharity_sponsorships_display_psPluginPage_section' 
		);

		add_settings_field( 
			'location_filter', __( 'Display Location Filter', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'location_filter_render'), 
			'psPluginPage', 'purecharity_sponsorships_display_psPluginPage_section' 
		);

		add_settings_field( 
			'iframe_sponsor', __( 'Enable Iframe Sponsor', 'wordpress' ), 
			array('Purecharity_Wp_Sponsorships_Admin', 'iframe_sponsor_render'), 
			'psPluginPage', 'purecharity_sponsorships_display_psPluginPage_section' 
		);
	}


	/**
	 * Renders the template selector for the single view.
	 *
	 * @since    1.0.1
	 */
	public static function single_view_template_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<select name="purecharity_sponsorships_settings[single_view_template]">
			<option value="">Inherit from the listing page</option>
			<?php foreach(get_page_templates() as $template){ ?>
				<option <?php echo $template == @$options['single_view_template'] ? 'selected' : '' ?>><?php echo $template ?></option>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Renders the live filter.
	 *
	 * @since    1.0.1
	 */
	public static function iframe_sponsor_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_sponsorships_settings[iframe_sponsor]" 
			<?php echo (isset($options['iframe_sponsor'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the powered by display.
	 *
	 * @since    1.0.0
	 */
	public static function hide_powered_by_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_sponsorships_settings[hide_powered_by]" 
			<?php echo (isset($options['hide_powered_by'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the age filter.
	 *
	 * @since    1.0.0
	 */
	public static function age_filter_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_sponsorships_settings[age_filter]" 
			<?php echo (isset($options['age_filter'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the gender filter.
	 *
	 * @since    1.0.0
	 */
	public static function gender_filter_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_sponsorships_settings[gender_filter]" 
			<?php echo (isset($options['gender_filter'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the location filter.
	 *
	 * @since    1.0.0
	 */
	public static function location_filter_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_sponsorships_settings[location_filter]" 
			<?php echo (isset($options['location_filter'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the main theme color picker.
	 *
	 * @since    1.0.0
	 */
	public static function main_color_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input type="text" name="purecharity_sponsorships_settings[plugin_color]" id="main_color" value="<?php echo @$options['plugin_color']; ?>">
		<?php
	}

	/**
	 * Renders the brading image uploader/url.
	 *
	 * @since    1.0.0
	 */
	public static function branding_image_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<label for="upload_image">
			<input id="branding_image" type="text" size="36" 
				name="purecharity_sponsorships_settings[branding_image]" value="<?php echo @$options['branding_image']; ?>" />
			<input id="upload_image_button" type="button" value="Upload Image" />
			<br />Enter an URL or upload an image for the branding image.
		</label>
		<?php
	}

	/**
	 * Renders the plugin style selector.
	 *
	 * @since    1.0.0
	 */
	public static function plugin_style_render(  ) { 
		$options = get_option( 'purecharity_sponsorships_settings' );
		?>
		<input 	type="radio" 
				style="float:left; margin: 57px 10px 0 0"
				name="purecharity_sponsorships_settings[plugin_style]" 
				value="pure-sponsorships-option1" 
				<?php echo $options['plugin_style'] == 'pure-sponsorships-option1' ? 'checked' : '' ?>
				/>
			<label><img src="https://purecharity.com/wp-content/uploads/2014/09/basic.png" width="320"></label>
		<br />
		<input 	type="radio" 
				style="float:left; margin: 57px 10px 0 0"
				name="purecharity_sponsorships_settings[plugin_style]" 
				value="pure-sponsorships-option2" 
				<?php echo $options['plugin_style'] == 'pure-sponsorships-option2' ? 'checked' : '' ?>
				/>
			<label><img src="https://purecharity.com/wp-content/uploads/2014/09/secondary.png" width="320"></label>
		<?php
	}


	/**
	 * Callback for use with Settings API.
	 *
	 * @since    1.0.0
	 */
	public static function settings_section_callback(  ) 
	{ 
		echo __( 'General settings for the sponsorships plugin.', 'wordpress' );
	}

	/**
	 * Callback for use with Settings API.
	 *
	 * @since    1.0.0
	 */
	public static function display_settings_section_callback(  ) 
	{ 
		echo __( 'Display settings for the sponsorships plugin.', 'wordpress' );
	}

	
	/**
	 * Creates the options page.
	 *
	 * @since    1.0.0
	 */
	public static function options_page()
	{
    ?>
    <div class="wrap">
      <form action="options.php" method="post" class="pure-settings-form">
				<?php
					echo '<img align="left" src="' . plugins_url( 'purecharity-wp-base/public/img/purecharity.png' ) . '" > ';
				?>
				<h2 style="padding-left:100px;padding-top: 20px;padding-bottom: 50px;border-bottom: 1px solid #ccc;">
					PureCharity&#8482; Sponsorships Settings
				</h2>
				
				<?php
				settings_fields( 'psPluginPage' );
				do_settings_sections( 'psPluginPage' );
				submit_button();
				?>
				
			</form>
    </div>
    <?php
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	    wp_enqueue_style('thickbox');

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'admin-uploader', plugins_url( 'admin/js/uploader-script.js' , dirname(__FILE__) ) );
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
	}

}
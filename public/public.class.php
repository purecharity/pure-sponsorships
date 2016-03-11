<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/public
 * @author     Rafael Dalprá <rafael.dalpra@toptal.com>
 */
class Purecharity_Wp_Sponsorships_Public {

	/**
	 * The sponsorship.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sponsorship    The sponsorship.
	 */
	public static $sponsorship;

	/**
	 * The sponsorships.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sponsorships    The sponsorships.
	 */
	public static $sponsorships;

	/**
	 * The full list of sponsorships.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sponsorships    The full list of sponsorships.
	 */
	public static $sponsorships_full;

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
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );


		wp_enqueue_style( 'pure-sponsorships-selects', plugin_dir_url( __FILE__ ) . 'css/jquery.simpleselect.css');

		$options = get_option( 'purecharity_sponsorships_settings' );
		// Allow the user to select a stylesheet theme
		if(isset($options['plugin_style'])){
			switch ($options['plugin_style']) {
			  case 'pure-sponsorships-option2':
			    $pure_style = 'pure-sponsorships-option2';
			    break;
			  case 'pure-sponsorships-option3':
			    $pure_style = 'pure-sponsorships-option3';
			    break;
			  default:
			    $pure_style = 'pure-sponsorships-option1';
			}
		}else{
		    $pure_style = 'pure-sponsorships-option1';
		}

		wp_enqueue_style( 'pure-sponsorships-style', plugin_dir_url( __FILE__ ) . 'css/'.$pure_style.'.css' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() { 

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('pure-sponsorships-selects-js', plugin_dir_url( __FILE__ ) . 'js/jquery.simpleselect.js', $this->version, false );

	}


	/**
	 * Listing entry for a single sponsorship.
	 *
	 * @since    1.0.0
	 */
	public static function listing(){
		$options = get_option( 'purecharity_sponsorships_settings' );
		$html = self::custom_css();
		$html .= '<div class="pcsponsor-container">';
		$html .= self::search_input();

		$html .= '<div class="pcsponsor-filters">';
		$html .= self::age_filter();
		$html .= self::gender_filter();
		$html .= self::location_filter();
		$html .= '</div>';

		foreach(self::$sponsorships->sponsorships as $sponsorship){
			$html .= '
				<div class="pcsponsor-item sponsorship_'.$sponsorship->id.'">
					<a href="?sponsorship='.$sponsorship->id.'">
						<div class="pcsponsor-image" style="background-image: url('.$sponsorship->images->small.');">
							'. ($sponsorship->is_sponsored ? '<p class="pcsponsor-full">Fully Sponsored</p>' : '') .'
						</div>
						<div class="pcsponsor-content">
							'.self::lower_listing_content($sponsorship, $options).'
						</div>
					</a>
				</div>
			';
		}

		$html .= '</div>';
	  $html .= Purecharity_Wp_Sponsorships_Paginator::page_links(self::$sponsorships->meta);
	  if(!isset($options['hide_powered_by'])){
  	$html .= Purecharity_Wp_Base_Public::powered_by();
  }

		return $html;
	}

	/**
	 * Single child view.
	 *
	 * @since    1.0.0
	 */
	public static function single(){

		$options = get_option( 'purecharity_sponsorships_settings' );
		$html = self::custom_css();

		if(isset($options['plugin_style']) && $options['plugin_style'] == 'pure-sponsorships-option3'){
			$html .= '
				<div class="pcs-rounded">

					<div class="info">
						'.self::render_slots(0).'
						<h1>'.self::$sponsorship->name.'</h1>
						'.self::render_age().'
						'.self::render_description().'
						'.self::render_about_section('pure-about').'
						'.self::render_custom_fields().'
					</div>

					<div class="pictures">

						<div class="control left">
							<a href="#"> < </a>
						</div>

						<div class="album">
							<div class="rail">
								<div class="picture" style="background-image: url('.self::$sponsorship->images->small.');">
								</div>
							</div>
							<ul class="controls">
								<li class="active"><a href="#picture-1"></a></li>
								<li><a href="#picture-2"></a></li>
							</ul>
							<div class="pcsponsor-single-select pc-third-view">
							'.self::render_sponsor_options().'
							</div>
						</div>

						<div class="control right">
							<a href="#"> > </a>
						</div>
					</div>
				</div>

				<div class="pcs-navigation">
					'.self::lower_links().'
				</div>
			';

		}else{
			$html .= '
				<div class="pcsponsor-single-container">
	        		<p class="pcsponsorships-return"><a href="#" onclick="javascript:history.go(-1); return false;">&#10094; Return to Sponsorship List</a></p>

					<div class="pcsponsor-single-image">
						<img src="'.self::$sponsorship->images->small.'" alt="'.self::$sponsorship->name.'"/>
					</div>
					<div class="pcsponsor-single-content">
						<div class="pcsponsor-single-info">
							<h4>'.self::$sponsorship->name.'</h4>
							'.self::render_slots(1).'
						</div>
						<div class="pcsponsor-single-desc">
							'.self::render_age().'
							'.self::render_description().'
							'.self::render_about_section('pure-about').'
							<p>'.self::render_custom_fields().'</p>
						</div>
						<div class="pcsponsor-single-select">
							'.self::render_sponsor_options().'
						</div>
					</div>
			';
		}

  	$html .= Purecharity_Wp_Base_Public::powered_by();

		return $html;
	}

	/**
	 * Renders slots.
	 *
	 * @since    1.4.2
	 */
	public static function render_slots($template=0){
		$total_available = self::$sponsorship->sponsors_goal;
		$taken = self::$sponsorship->quantity_taken;
		if((int)$taken > (int)$total_available){ $taken = $total_available; }

		$html = '';
		if((int)$total_available > 1){
			if($template == 0){
				$html .= '<div class="slots">
										<ul class="no-padding">'.self::the_bullets(self::$sponsorship).'</ul>
										'. self::sponsorship_slots_text() .'
									</div>';
			}else{
				$html .= '<ul class="pcsponsor-status-buttons pcsponsor-single-status-buttons">
										'.self::the_bullets(self::$sponsorship).'
									</ul>
									'. self::sponsorship_slots_text();
			}
		}
		return $html;
	}

	/**
	 * Renders the description.
	 *
	 * @since    1.4.2
	 */
	public static function render_description(){
		$html = '';
		if(!empty(self::$sponsorship->description)){
			$html = '<p class="pure-desc">
								'.self::$sponsorship->description.'
							</p>';
		}
		return $html;
	}

	/**
	 * Renders the age display.
	 *
	 * @since    1.4.1
	 */
	public static function render_age(){
		$html = '';
		if(!empty(self::$sponsorship->age)){
			$html = '<p class="pure-desc"><small>
							<strong>Age:</strong> '.self::$sponsorship->age.'
						</small></p>';
		}
		return $html;
	}

	/**
	 * Renders the about optional section.
	 *
	 * @since    1.4
	 */
	public static function render_about_section($class=''){
		$html = '';
		if(isset(self::$sponsorship->about) && self::$sponsorship->about != ''){
			$html = '<p class="'.$class.'">'.self::$sponsorship->about.'</p>';
		}
		return $html;
	}

	/**
	 * Renders the lower content of the listing options.
	 *
	 * @since    1.1
	 */
	public static function lower_listing_content($sponsorship, $options){
		$total_available = $sponsorship->sponsors_goal;

		$available = $sponsorship->sponsors_goal - $sponsorship->quantity_taken;
 		if($available < 0){ $available = 0; }

		$components = array();
		$components['title'] = '<h4>'.$sponsorship->name.'</h4>';
		if((int)$total_available > 1){
			$components['bullets'] = '<ul class="pcsponsor-status-buttons">'.self::the_bullets($sponsorship).'</ul>';
			$components['info'] = '<p class="pcsponsor-status">
														 	'.$available.' of '.$total_available.'
														 	'.pluralize($total_available, 'Sponsorship').'
														 	Available
														 </p>';
		}else{
			$components['bullets'] = '';
			$components['info'] = (($sponsorship->is_sponsored && (isset($options['plugin_style']) && $options['plugin_style'] == 'pure-sponsorships-option3')) ? '<p>Sponsored</p>' : '');
		}
		if(isset($options['plugin_style']) && $options['plugin_style'] == 'pure-sponsorships-option3'){
			return $components['title'].$components['info'].$components['bullets'];
		}else{
			return $components['title'].$components['bullets'].$components['info'];
		}
	}

	/**
	 * Generates the age filter.
	 *
	 * @since    1.0.0
	 */
	public static function age_filter(){
		$options = get_option( 'purecharity_sponsorships_settings' );
		if(isset($options["age_filter"])){
			return '<select data-type="age" name="age">
				<option value="0">Select Age</option>
				<option '. ( (isset($_GET['age']) && $_GET['age'] == '0-4') ? 'selected' : '') .' value="0-4">0-4</option>
				<option '. ( (isset($_GET['age']) && $_GET['age'] == '5-8') ? 'selected' : '') .' value="5-8">5-8</option>
				<option '. ( (isset($_GET['age']) && $_GET['age'] == '9-12') ? 'selected' : '') .' value="9-12">9-12</option>
				<option '. ( (isset($_GET['age']) && $_GET['age'] == '13') ? 'selected' : '') .' value="13">13+</option>
			</select>';
		}
	}

	/**
	 * Generates the age filter.
	 *
	 * @since    1.0.0
	 */
	public static function gender_filter(){
		$options = get_option( 'purecharity_sponsorships_settings' );
		if(isset($options["gender_filter"])){
			return '<select data-type="gender" name="gender">
				<option value="0">Select Gender</option>
				<option '. ( (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : '') .' >Male</option>
				<option '. ( (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : '') .' >Female</option>
			</select>';
		}
	}

	/**
	 * Generates the age filter.
	 *
	 * @since    1.0.0
	 */
	public static function location_filter(){
		$options = get_option( 'purecharity_sponsorships_settings' );
		if(isset($options["gender_filter"])){
			$html = "";
			// Grab the locations for the filter
			$locations = array();
			foreach (self::$sponsorships_full->sponsorships as $sponsorship) {
				$locations[$sponsorship->location] = true;
			}
			asort($locations);
			$html .= '<select data-type="location" name="location">';
			$html .= '<option value="0">Select Country</option>';
			foreach ($locations as $location => $val) {
				$html .= '<option '. ( (isset($_GET['location']) && $_GET['location'] == $location) ? 'selected' : '') .'>'. $location .'</option>';
			}
			$html .= '</select>';
			return $html;
		}
	}

	/**
	 * Generates the bullets for the sponsorship.
	 *
	 * @since    1.0.0
	 */
	public static function the_bullets($sponsorship){
		$total_available = $sponsorship->sponsors_goal;
		$taken = $sponsorship->quantity_taken;
		if((int)$taken > (int)$total_available){ $taken = $total_available; }

		$html = '';
		if((int)$total_available > 1){
			for ($i = 1; $i <= $total_available; $i++) {
				$klass = ($i <= $taken) ? 'pcsponsor-taken' : '';
		   	$html .= '<li class="'. $klass .'"></li>';
			}
		}
		return $html;

	}



	/**
	 * Custom CSS in case the user has chosen to use another color.
	 *
	 * @since    1.0.0
	 */
	public static function custom_css()
	{
		$base_settings = get_option( 'pure_base_settings' );
		$pf_settings = get_option( 'purecharity_sponsorships_settings' );

		// Default theme color
		if(empty($pf_settings['plugin_color'])){
			if($base_settings['main_color'] == NULL || $base_settings['main_color'] == ''){
				$color = '#CA663A';
			}else{
				$color = $base_settings['main_color'];
			}
		}else{
			$color = $pf_settings['plugin_color'];
		}

		$scripts = '
			<style>
				.single-sponsorship .ps-taken,
				.single-sponsorship .simpleselect .placeholder,
				.single-sponsorship .styledButton ,
				.pcs-rounded .info .slots ul li.taken,
				.pure-button { background: '. $color .' !important; color: #FFF !important; }
				.pcsponsorships-return a,
				.pcs-rounded .info .slots ul li.pcsponsor-taken,
				.pcs-navigation a span
				.single-sponsorship a { color: '. $color .' !important; }
			</style>
		';

		return $scripts;
	}


	/**
	 * Renders the available sponsorships slot for the single view. Returns blank if there is only one.
	 *
	 * @since    1.1.2
	 */
	public static function sponsorship_slots_text(){

		$total_available = (int)self::$sponsorship->sponsors_goal;

		$available = self::$sponsorship->sponsors_goal - self::$sponsorship->quantity_taken;
 		if($available < 0){ $available = 0; }

		$html = '';

		if($total_available > 1 && $available > 0){
			$html = '
			<span>
				'.$available.' of '.$total_available.'
				'.pluralize($total_available, 'Sponsorship').'
				Available
			</span>
			';
		}elseif($total_available > 1 && $available == 0){
			$html = '<span>Fully Sponsored</span>';
		}elseif($available == 0){
			$html = '<span>Sponsored</span>';
		}

		return $html;
	}


	/**
	 * Renders the lower links for the single kid view when using layout option 3.
	 *
	 * @since    1.1
	 */
	public static function lower_links(){
		$options = get_option( 'purecharity_sponsorships_settings' );

		$html = '';
		if(isset($options['back_link'])){
			$html .= '<a href="#" class="back"><span> < </span> Back to all kids</a>';
		}
		if(isset($options['more_link'])){
			$html .= '<a href="#" class="learn-more">Learn more about sponsorships <span> > </span></a>';
		}

		return $html;
	}

	/**
	 * Renders the custom fields for the single kid view.
	 *
	 * @since    1.1
	 */
	public static function render_custom_fields(){
		$options = get_option( 'purecharity_sponsorships_settings' );
		$fields_config = explode(";", $options['allowed_custom_fields']);

		$custom_fields = Array();
		foreach($fields_config as $key => $value){
			$parts = explode('|', $value);
			$custom_fields[$parts[0]] = @$parts[1];
		}


		$html = '';
		foreach($custom_fields as $key => $value){
			if(isset(self::$sponsorship->custom_fields->$key)){
				$html .= "<b>".$value."</b>: ".self::$sponsorship->custom_fields->$key."<br />";
			}
		}

		if($html != ''){
			$exp = explode(' ', self::$sponsorship->name);
			$return = "<h4>about ".$exp[0]."</h4><p>$html</p>";
		}else{
			$return = '';
		}

		return $return;
	}

	/**
	 * Renders the sponsor options for the single kid view.
	 *
	 * @since    1.0.0
	 */
	public static function render_sponsor_options(){

		$options = get_option( 'purecharity_sponsorships_settings' );

		$available = self::$sponsorship->sponsors_goal - self::$sponsorship->quantity_taken;
 		if($available < 0){ $available = 0; }

		$html = '<form method="get" action="'.Purecharity_Wp_Base_Public::pc_url().'/sponsorships/'. self::$sponsorship->id .'/fund" class="pcsponsor-fund-form">';

		if((int)self::$sponsorship->sponsors_goal > 1 && $available > 0){
			$html .= '<select id="sponsorship_supporter_shares" name="amount">';
			$html .= '<option>Choose Sponsorship Level</option>';
			for ($i = 1 ; $i <= $available ; $i++) {
				$termName = 'Sponsorship';
				if ($i > 1) {
					$termName = 'Sponsorships';
				}
				$html .= '<option value="'. (self::$sponsorship->funding_per * $i) .'.0">'. $i .' '. $termName .' - $'. (self::$sponsorship->funding_per * $i) .'.00 Monthly</option>';
			}
			$html .= '</select>';
		}else{
			$html .= '<input type="hidden" name="amount" value="'.self::$sponsorship->funding_per.'" />';
		}
		if($available > 0){
			$html .= '<a class="pure-button submit" href="#">Sponsor</a>';
		}
		$html .= '</form>';
		return $html;
	}

	/**
	 * Global search input.
	 *
	 * @since    1.1.1
	 */
	public static function search_input(){

		$options = get_option( 'purecharity_sponsorships_settings' );

		if(isset($options["search_input"])){

			$html = '
				<div class="sp-filtering">
					<form method="get">
						<fieldset class="livefilter sp-livefilter">
							<legend>
								<label for="livefilter-input">
									<strong>Search Sponsorships:</strong>
								</label>
							</legend>
							<input id="livefilter-input" class="sp-livefilter-input" value="'.@$_GET['query'].'" name="query" type="text">
							<button class="sp-filtering-button" type="submit">Filter</button>
							'. (@$_GET['query'] != '' ? '<a href="#" onclick="$(this).prev().prev().val(\'\'); $(this).parents(\'form\').submit(); return false;">Clear filter</a>' : '') .'
						</fieldset>
					</form>
				</div>
			';
		}else{
			$html = '';
		}
		return $html;
	}
}

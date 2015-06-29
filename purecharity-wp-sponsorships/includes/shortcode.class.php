<?php

/**
 * Used on public display of the sponsorship(s)
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/includes
 */

/**
 * Used on public display of the sponsorship(s).
 *
 * This class defines all the shortcodes necessary.
 *
 * @since      1.0.0
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/includes
 * @author     Rafael Dalprá <rafael.dalpra@toptal.com>
 */
class Purecharity_Wp_Sponsorships_Shortcode {


  /**
   * The Base Plugin.
   *
   * @since    1.0.0
   * @access   public
   * @var      Object    $base_plugin    The Base Plugin.
   */
  public static $base_plugin;

  /**
   * Initialize the class and Base Plugin functionality.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->actions = array();
    $this->filters = array();

  }

  /**
   * Initialize the shortcodes to make them available on page runtime.
   *
   * @since    1.0.0
   */
  public static function init()
  {
    if(Purecharity_Wp_Sponsorships::base_present()){
      add_shortcode('sponsorships', array('Purecharity_Wp_Sponsorships_Shortcode', 'sponsorships_shortcode'));
      add_shortcode('sponsorship', array('Purecharity_Wp_Sponsorships_Shortcode', 'sponsorship_shortcode'));
      add_shortcode('sponsorship_child', array('Purecharity_Wp_Sponsorships_Shortcode', 'sponsorship_child_shortcode'));

      self::$base_plugin = new Purecharity_Wp_Base();
    }
  }


  /**
   * Sponsorships list shortcode.
   *
   * @since    1.0.0
   */
  public static function sponsorships_shortcode($atts)
  {

    // Set up and retrieve attributes
    $options = shortcode_atts( array(
      'per_page' => false
    ), $atts );

    $sponsorship_id = $atts["program_id"];

    if (isset($_GET['child_id'])) {
      // In case it's a single child view
      return self::sponsorship_child_shortcode(array('child_id' => $_GET['child_id']));
    } else if($sponsorship_id){
      $filters = '';
      $age = '';
      $gender = '';
      $location = '';

      // Check for any filters, validating and sanitizing along the way
      // Append valid filters to $filters
      $gender = '';
      if (isset($_GET['gender'])) {
        $gender = ucfirst(strtolower($_GET['gender']));

        if ($gender == 'Male' || $gender == 'Female') {
          $filters = '&gender='. $gender;
        }
      }

      $range1 = $range2 = $range3 = $range4 = '';

      $age = '';
      if (isset($_GET['age'])) {
        $age = $_GET['age'];
        if (preg_match('/[0-9-]*/', $_GET['age'])) {
          // Split the age
          $ages = explode('-', $_GET['age']);

          if (isset($ages[0]) && isset($ages[1])) {
            $filters .= '&min_age='. $ages[0];
          }

          if (isset($ages[0]) && !isset($ages[1])) {
            $filters .= '&max_age='. $ages[0];
          }

          if (isset($ages[1])) {
            $filters .= '&max_age='. $ages[1];
          }
        }
      }

      $country = '';
      if (isset($_GET['country'])) {
        $country = urlencode(sanitize_text_field($_GET['country']));
        $filters .= '&country='. $country;
      }

      $country = '';
      if (isset($_GET['country'])) {
        $country = urlencode(sanitize_text_field($_GET['country']));
        $filters .= '&country='. $country;
      }

      $full_filters = $filters;
      $full_filters .= '&limit='. 9999;


      if (isset($_GET['_page'])) {
        $filters .= '&page='. (int) $_GET['_page'];
      }

      if ($limit = $options['per_page']) {
        $filters .= '&limit='. (int) $limit;
      }

      // Grab the sponsorships 
      $sponsorships = self::$base_plugin->api_call('sponsorships?sponsorship_program_id='. $sponsorship_id . $filters);
      $sponsorships_full = self::$base_plugin->api_call('sponsorships?sponsorship_program_id='. $sponsorship_id . $full_filters);

      // Set up the page url for filtering
      $pageUrl = explode('?', $_SERVER['REQUEST_URI'], 2);
      $pageUrl = $pageUrl[0];

      Purecharity_Wp_Sponsorships_Public::$sponsorships = $sponsorships;
      Purecharity_Wp_Sponsorships_Public::$sponsorships_full = $sponsorships_full;
      return Purecharity_Wp_Sponsorships_Public::listing();
    }
  }

  /**
   * Sponsorships child view shortcode.
   *
   * @since    1.0.0
   */
  public static function sponsorship_child_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'child_id' => false
    ), $atts );

    if ($options['child_id']) {
      $sponsorship = self::$base_plugin->api_call('sponsorships/'. $options['child_id']);

      Purecharity_Wp_Sponsorships_Public::$sponsorship = $sponsorship->sponsorship;

      return Purecharity_Wp_Sponsorships_Public::single();
    }
  }
}
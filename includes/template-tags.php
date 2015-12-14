<?php
/**
 * Template tags for sponsorships
 *
 * @link       http://purecharity.com
 * @since      1.3
 *
 * @package    Purecharity_Wp_Sponsorships
 * @subpackage Purecharity_Wp_Sponsorships/includes
 */

/**
 * Sponsorships listing.
 *
 * @since    1.3
 */
function pc_sponsorships($program_slug){
  return pc_base()->api_call('sponsorships?sponsorship_program_id=' . $program_slug);
}

/**
 * Single Sponsorship.
 *
 * @since    1.3
 */
function pc_sponsorship($sponsorship){
  return pc_base()->api_call('sponsorships/' . $sponsorship);
}



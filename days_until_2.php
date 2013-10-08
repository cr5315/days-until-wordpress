<?php
/**
 * Plugin Name: Simple Shortcode Days Until
 * Plugin URI: http://cr5315.com
 * Description: Prints the days until something
 * Version: 1.0
 * Author: Ben Butzow
 * Author URI: http://cr5315.com
 * License: WTFPL-2.0
**/

/**
 * Use: [daysuntil year="2014" month="01" day="01" title="New Years Day"/]
 */
function du2_shortcode($atts) {
	extract(shortcode_atts(array(
		'year' => '2013',
		'month' => '01',
		'day' => '01',
		'title' => 'NO_TITLE'),
		$atts));
		
		$days = du2_getDaysUntil($day, $month, $year);
		
		if ($days > 1) return $days . " days until " . $title . ".";
		elseif ($days == 1) return $days . " day until " . $title . ".";
		elseif ($days < 0) return ($days * -1) . " days since " . $title . ".";
		else return $title . " is today!";
}
add_shortcode('daysuntil', 'du2_shortcode');

function du2_getDaysUntil($day, $month, $year) {
	$yearArray = du_get_year_array();	
	
	$cdate = date("Y-m-d");
	$fdate = $year . "-" . $month . "-" . $day;
	
	return floor((strtotime($fdate) - strtotime($cdate)) / 86400);
}
?>
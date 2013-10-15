<?php
/**
 * Plugin Name: Simple Shortcode Days Until 3
 * Plugin URI: http://cr5315.com
 * Description: Prints the years, months, and days until something
 * Version: 1.0
 * Author: Ben Butzow
 * Author URI: http://cr5315.com
 * License: WTFPL-2.0
**/

function du3_shortcode($atts) {
	extract(shortcode_atts(array(
	    'year' => '2013',
		'month' => '01',
		'day' => '01',
		'title' => 'event'),
		$atts));
		
		return du3_getDaysUntil($day, $month, $year, $title);
}
add_shortcode('daysuntil3', 'du3_shortcode');

function du3_getDaysUntil($day, $month, $year, $title) {
	$cdate = date("Y-m-d");
	$fdate = $year . "-" . $month . "-" . $day;
	
	$days = round((strtotime($fdate) - strtotime($cdate)) / 86400);
	$months = round((strtotime($fdate) - strtotime($cdate)) / 2.63e+6);
	$years = floor((strtotime($fdate) - strtotime($cdate)) / 3.156e+7);
	
	$isPast = false;
	if ($days < 0 && $months < 0 && $years < 0) {
		$isPast = true;
		$years = ceil((strtotime($fdate) - strtotime($cdate)) / 3.156e+7) * -1;
		$months = $months * -1;
		$days = $days * -1;
	}
	for ($i = 0; $i < $years; $i++) {
		$months -= 12;
	}
	$days -= du3_subtract_years($years);
	$days -= du3_subtract_months($months);
		
	return du3_format_time_until($days, $months, $years, $title, $isPast);
}

function du3_format_time_until($days, $months, $years, $title, $isPast) {
	$time_until = "";
	
	// Years
	if ($years == 1) {
		$time_until = $time_until . $years . " year, ";
	} else if ($years > 1 || $years < 0) {
		$time_until = $time_until . $years . " years, ";
	}
	
	// Months
	if ($months == 1) {
		$time_until = $time_until . $months . " month, ";
	} else if ($months > 1 || $months < 0) {
		$time_until = $time_until . $months . " months, ";
	}
	
	// Days
	if ($days == 1) {
		$time_until = $time_until . $days . " day ";
	} else if ($days > 1 || $days <= 0) {
		$time_until = $time_until . $days . " days ";
	}
	
	if ($isPast) {
		return $time_until . " since ". $title;
	} else {
		return $time_until . " until ". $title;
	}
	
}

function du3_subtract_months($count) {
	$month = date("m");
	$days_to_subtract = 0;
	
	for ($i = 0; $i < $count; $i++) {
		switch ($month) {
		case 1: // January
			$days_to_subtract += 31;
			break;
		case 2: // February
			if (du3_is_leap_year()) {
				$days_to_subtract += 29;
			} else {
				$days_to_subtract += 28;
			}
			break;
		case 3: // March
			$days_to_subtract += 31;
			break;
		case 4: // April
			$days_to_subtract += 30;
			break;
		case 5: // May
			$days_to_subtract += 31;
			break;
		case 6: // June
			$days_to_subtract += 30;
			break;
		case 7: // July
			$days_to_subtract += 31;
			break;
		case 8: // August
			$days_to_subtract += 31;
			break;
		case 9: // September
			$days_to_subtract += 30;
			break;
		case 10: // October
			$days_to_subtract += 31;
			break;
		case 11: // November
			$days_to_subtract += 30;
			break;
		case 12: // December
			$days_to_subtract += 31;
			break;
		}
		if ($month == 12) $month = 1;
		else $month++;
	}
	
	return $days_to_subtract;
}

function du3_is_leap_year() {
	$is_leap_year = date("L");
	if ($is_leap_year == 1) {
		return true;
	} else {
		return false;
	}
}

function du3_subtract_years($count) {
	$days_to_subtract = 0;
	$leap_year = 2012;
	$current_year = date('Y');
	
	for ($i = 0; $i < $count; $i++) {
		if (($current_year % 4) == 0) {
			// It's a leap year
			$days_to_subtract += 366;
		} else {
			$days_to_subtract += 365;
		}
		$current_year -= 1;
	}
	
	return $days_to_subtract;
}

?>
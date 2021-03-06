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
	
	$days = floor((strtotime($fdate) - strtotime($cdate)) / 86400);
	$months = floor((strtotime($fdate) - strtotime($cdate)) / 2.63e+6);
	$years = floor((strtotime($fdate) - strtotime($cdate)) / 3.156e+7);
	
	$isPast = false;
	if ($days < 0 && $months < 0 && $years < 0) {
		$isPast = true;
		$years = ceil((strtotime($fdate) - strtotime($cdate)) / 3.156e+7) * -1;
		$months = round((strtotime($fdate) - strtotime($cdate)) / 2.63e+6) * -1;
		$days = ceil((strtotime($fdate) - strtotime($cdate)) / 86400) * -1;
	}
	for ($i = 0; $i < $years; $i++) {
		$months -= 12;
	}
	$days -= du3_subtract_years($years, $isPast);
	$days -= du3_subtract_months($months, $isPast);
		
	return du3_format_time_until($days, $months, $years, $title, $isPast);
}

function du3_subtract_months($count, $isPast) {
	$month = date("m");
	$year = date("Y");
	$days_to_subtract = 0;
	
	for ($i = 0; $i < $count; $i++) {
		switch ($month) {
		case 1: // January
		case 3: // March
		case 5: // May
		case 7: // July
		case 8: // August
		case 10: // October
		case 12: // December
			$days_to_subtract += 31;
			break;
		case 4: // April
		case 6: // June
		case 9: // September
		case 11: // November
			$days_to_subtract += 30;
			break;
		case 2: // February
			if (du3_is_leap_year($year)) {
				$days_to_subtract += 29;
			} else {
				$days_to_subtract += 28;
			}
			break;
		}
		if ($month == 12) {
			$month = 1;
		} 
		else $month++;
		
		if (($count % 12) == 0) {
			if ($isPast) $year--;
			else $year++;
		}
	}
	
	return $days_to_subtract;
}

function du3_is_leap_year($year) {
	if (($year % 4) == 0) {
		return true;
	} else {
		return false;
	}
}

function du3_subtract_years($count, $isPast) {
	$days_to_subtract = 0;
	$current_year = date('Y');
	
	for ($i = 0; $i < $count; $i++) {
		if (du3_is_leap_year($current_year)) {
			// It's a leap year
			$days_to_subtract += 366;
		} else {
			$days_to_subtract += 365;
		}
		if ($isPast) $current_year--;
		else $current_year++;
	}
	
	return $days_to_subtract;
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

?>
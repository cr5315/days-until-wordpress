<?php
/**
 * Plugin Name: Simple Days Until
 * Plugin URI: http://cr5315.com
 * Description: Prints the days until something
 * Version: 1.0
 * Author: Ben Butzow
 * Author URI: http://cr5315.com
 * License: WTFPL-2.0
**/

function du_shortcode($attrs) {
	$event = get_option('du_event');
	$days = getDaysUntil();
	
	if ($days > 1) return $days . " days until " . $event . ".";
	elseif ($days = 1) return $days . " day until " . $event . ".";
	elseif ($days < 0) return ($days * -1) . " days since " . $event . ".";
	else return $event . " is today!";
	
}
add_shortcode('days_until', 'du_shortcode');

function getDaysUntil() {
	$yearArray = du_get_year_array();	
		
	$day = get_option('du_day');
	$month = get_option('du_month') + 1; // offset by 1 because 0 != January
	$year = $yearArray[get_option('du_year')];	
	
	$cdate = date("Y-m-d");
	$fdate = $year . "-" . $month . "-" . $day;
	
	return floor((strtotime($fdate) - strtotime($cdate)) / 86400);
}

function du_create_admin_menu() {
	add_menu_page("Days Until", "Days Until", 'administrator', __FILE__,
		'du_settings_page');
		
		add_action('admin_init', 'du_register_settings');
}
add_action('admin_menu', 'du_create_admin_menu');

function du_register_settings() {
	register_setting('du_settings', 'du_event');
	register_setting('du_settings', 'du_day');
	register_setting('du_settings', 'du_month');
	register_setting('du_settings', 'du_year');
}

function du_settings_page() {
	?>
	<div class="wrap">
		<h2>Days Until</h2>
		
		<form method="post" action="options.php">
			
			<?php settings_fields('du_settings'); ?>
			<?php do_settings_sections('du_settings'); ?>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Event Title</th>
				<td><input type="text" name="du_event" value=" <?php echo get_option('du_event'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Event Date</th>
				<td>
					<label for="du_day">Day</label>
					<select name="du_day" id="du_day">
						<?php
							$days = du_get_day_array();
							$i = 1;
							$isSelected = get_option('du_day');
							foreach ($days as $row) {
								echo "<option value='$i' ". (($i == $isSelected) ? "selected='selected'":"").">$i</option>";
								$i++;
							}
							
						?>
					</select>
					
					<label for="du_month">Month</label>
					<select name="du_month" id="du_month">
						<?php
							$months = du_get_month_array();
							$isSelected = get_option('du_month');
							$i = 0;
							foreach ($months as $row) {
								echo "<option value='$i' ". (($i == $isSelected) ? "selected='selected'":"").">$months[$i]</option>";
								$i++;
							}
							
							?>
					</select>
					
					<label for="du_year">Year</label>
					<select name="du_year" id="du_year">
						<?php
							$years = du_get_year_array();
							$isSelected = get_option('du_year');
							$i = 0;
							foreach ($years as $row) {
								echo "<option value='$i' ". (($i == $isSelected) ? "selected='selected'":"").">$years[$i]</option>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
		</table>
		
		<?php submit_button(); ?>
			
		</form>
	</div>
	
	
	<?php
}



function du_get_day_array() {
	$array = array();
	
	for ($i = 0; $i < 31; $i++) {
		$array[$i] = $i;
	}
	
	return $array;
}

function du_get_month_array() {
	$array = array();
	
	for ($i = 0; $i < 12; $i++) {
		switch($i) {
			case '0':
				$array[$i] = "January";
				break;
			case '1':
				$array[$i] = "February";
				break;
			case '2':
				$array[$i] = "March";
				break;
			case '3':
				$array[$i] = "April";
				break;
			case '4':
				$array[$i] = "May";
				break;
			case '5':
				$array[$i] = "June";
				break;
			case '6':
				$array[$i] = "July";
				break;
			case '7':
				$array[$i] = "August";
				break;
			case '8':
				$array[$i] = "September";
				break;
			case '9':
				$array[$i] = "October";
				break;
			case '10':
				$array[$i] = "November";
				break;
			case '11':
				$array[$i] = "December";
				break;
		}
	}
	
	return $array;
}

function du_get_year_array() {
	$array = array();
	
	$year = 2013;
	for ($i = 0; $i < 20; $i++) {
		$array[$i] = $year;
		
		$year++;
	}
	
	return $array;
}
?>
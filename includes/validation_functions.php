<?php
	// Error Check Functions
	function has_presence($value) {
		return isset($value) && $value !== "";
	}
	function has_max_length($value, $max) {
		return strlen($value) <= $max;
	}

	// Error Report Function
	function form_errors ($errors=array()) {
		$output = ""; 
		if (!empty($errors)) {
			$output = "<div class=\"error\">";
			$output .= "<span class=\"sorry\">Sorry, but there was a problem with your submission.</span>";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$output .= "<li>{$error}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		} 
		return $output;
	}
?>
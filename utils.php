<?php


function check_message_base64($message) {

	$base64_charset = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=");
	if (strlen($message) > 128) { #Reject longer messages
		return False;
	}
	if (strlen($message) < 1) {
		return False;
	}
	$message_content = str_split($message);
	$qualifying_message = True;
	foreach ($message_content as $charkey) {
		if (!(in_array($charkey, $base64_charset))) {
			$qualifying_message = False;
			break;
		}
	}
	unset($message_content);
	if ($qualifying_message == False) {
		return False;
	}
	else {
		return True;
	}
}


function bad_request() {
	//Generate a HTTP bad request.
	echo "400: Bad Request.";
	header('HTTP/1.0 400 Bad Request');
	session_destroy();
	die();
}

function read_date($day_month, $mode) {
	//reads dates in "DD/MM" format.
	//$mode: if $mode == 1, return a set of day and month as is; otherwise return in printable format.
	$date_explode = explode("/", $day_month);
	if ((strlen($date_explode[0]) != 2) || (strlen($date_explode[1]) != 2)) {
		//occurs if the format is not correctly in "DD/MM"
		if ($mode == 1) {
			$return_date[0] = "01";
			$return_date[1] = "01";
		}
		else {
			$return_date[0] = 1;
			$return_date[1] = "January";
		}
	}
	else {
		if ($mode == 1) {
			$return_date[0] = $date_explode[0];
			$return_date[1] = $date_explode[1];
		}
		else {
			$return_date[0] = intval($date_explode[0]);
			$dateObject   = DateTime::createFromFormat('!m', intval($date_explode[1]));
			$return_date[1] = $dateObject->format('F');
		}
	}
	return $return_date;
}

function array_sorted_check($integer_array) {
	//Check if an array of integers is already sorted
	$arrayA = $integer_array;
	$arrayB = $integer_array;
	sort($arrayB);
	if ($arrayA == $arrayB) {
		return true;
	}
	else {
		return false;
	}
}
?>
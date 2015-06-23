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


?>
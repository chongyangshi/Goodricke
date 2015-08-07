<?php

//This is the cron job script for sending emails. It should be ran at some point every evening, for example, every day at 8pm.

if (php_sapi_name() != 'cli') {
	die("Access Denied.");
}

chdir(dirname(__FILE__));

require "config.php";
require "utils.php";
require 'vendor/autoload.php';
use Mailgun\Mailgun;

$today = getdate();
$today_date = getdate()[0];

$today_type = getdate()['wday'];

switch ($today_type) {
	case 0:
		$today_type = "sunday";
		break;
	case 1:
		$today_type = "monday";
		break;
	case 2:
		$today_type = "tuesday";
		break;
	case 3:
		$today_type = "wednesday";
		break;
	case 4:
		$today_type = "thursday";
		break;
	case 5:
		$today_type = "friday";
		break;
	case 6:
		$today_type = "saturday";
		break;
}

if (($today['year'] != $termDates['academicYear']['starts']) && ($today['year'] != $termDates['academicYear']['ends'])) {
	error_log("Goodricke cron.php: not sending reminder emails as the term years set in config.php are invalid.");
	die();
}

$term_period = array (
	"autumn" => array("starts" => strtotime(read_date($termDates['autumn']['starts'], 1)[0]."-".read_date($termDates['autumn']['starts'], 1)[1]."-".$termDates['academicYear']['starts']), "ends" => strtotime(read_date($termDates['autumn']['ends'], 1)[0]."-".read_date($termDates['autumn']['ends'], 1)[1]."-".$termDates['academicYear']['starts'])), 
	"spring" => array("starts" => strtotime(read_date($termDates['spring']['starts'], 1)[0]."-".read_date($termDates['spring']['starts'], 1)[1]."-".$termDates['academicYear']['ends']), "ends" => strtotime(read_date($termDates['spring']['ends'], 1)[0]."-".read_date($termDates['spring']['ends'], 1)[1]."-".$termDates['academicYear']['ends'])), 
	"summer" => array("starts" => strtotime(read_date($termDates['summer']['starts'], 1)[0]."-".read_date($termDates['summer']['starts'], 1)[1]."-".$termDates['academicYear']['ends']), "ends" => strtotime(read_date($termDates['summer']['ends'], 1)[0]."-".read_date($termDates['summer']['ends'], 1)[1]."-".$termDates['academicYear']['ends']))
);

$dates_array = array (
	0=>$term_period['autumn']['starts'],
	1=>$term_period['autumn']['ends'],
	2=>$term_period['spring']['starts'],
	3=>$term_period['spring']['ends'],
	4=>$term_period['summer']['starts'],
	5=>$term_period['summer']['ends']
);

if (!(array_sorted_check($dates_array))){
	//Test if timestamp is in increasing order
	error_log("Goodricke cron.php: not sending reminder emails as the term dates set in config.php are invalid.");
	die();
}

if ((($today_date>=$dates_array[0]) && ($today_date<=$dates_array[1])) || (($today_date>=$dates_array[2]) && ($today_date<=$dates_array[3])) || (($today_date>=$dates_array[4]) && ($today_date<=$dates_array[5]))) {
	$current_day_type = "TERMTIME";
}
else {
	$current_day_type = "VACATION";
}

$cron_sql = new SQLite3('Goodricke.sqlite');

if ($current_day_type == "TERMTIME") {
	$query_string = 'SELECT yorkemail,unsubcode FROM subscriptions WHERE '.$today_type.' = 1 AND termtime_reminders = 1 AND active = 1;';
	$sending_query = $cron_sql->prepare($query_string);
}
else {
	$query_string = 'SELECT yorkemail,unsubcode FROM subscriptions WHERE '.$today_type.' = 1 AND vacation_reminders = 1 AND active = 1;';
	$sending_query = $cron_sql->prepare($query_string);
}

if ($sending_query_result = $sending_query->execute()){
	//Generate the list of emails to send notifications to.
	$receivers = array();

	while ($row = $sending_query_result->fetchArray(SQLITE3_ASSOC)) {
		$receivers[] = array($row['yorkemail'], $row['unsubcode']);
	}

	$mg = new Mailgun($mailgunAPIKey);
    	$sender_address = "no-reply@".$mailgunDomain;

    	foreach ($receivers as $client_email) {
		$unsubscribe_address = $systemDomainAddress."unsubscribe.php?e=".base64_encode($client_email[0])."&v=".$client_email[1];

		$reminder_message = "
Hi there,

This is a weekly email reminder from Goodricke Cleaning Reminder Service. You have scheduled cleaning reminder(s) to be sent to you every week.

If you no longer wish to receive reminders, please follow the link below to unsubscribe:
".
$unsubscribe_address."

Best regards,

Goodricke Reminder Service

".$systemDomainAddress;
		$mg->sendMessage($mailgunDomain, array('from'    => $sender_address, 
                                               'to'      => $client_email[0], 
                                               'subject' => 'Goodricke Cleaning Reminder '.date('d/m/Y', $today_date), 
                                               'text'    => $reminder_message));
	}

}
else {
	error_log("Goodricke cron.php: error querying database.");
	die();
}


?>

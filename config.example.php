<?php

//This is the configuration file for the system.

//Set the domain address to the full domain of the site, WITH the trailing slash.
$systemDomainAddress = "https://goodricke.example.com/";

//reCaptcha keys and secrets.
//Key and secret can be obtained at https://www.google.com/recaptcha/
$reCaptchaSiteKey = "";        //reCaptcha Site Key
$reCaptchaSiteSecretKey = "";  //reCaptcha Site Secret Key

//Change the settings if you are using a CDN service for your server. Setting reserved for future features.
$CDNXForwardedFor = False; //Set this to True if your server is behind a CDN/Firewall service like CloudFlare.

//Change the below to a string of your liking.
$emailHashingSalt = "ThisIsASalt";

//Set mailgun.com credentials below, you should have finished setting up your domain at mailgun.com
$mailgunAPIKey = "key-xxxxxxxxxxxx1234567890";
$mailgunDomain = "goodricke.example.com"; //exactly the same as the domain you set up at mailgun.com

//Set the term dates below, be careful.
$termDates = array (
	"academicYear" => array("starts" => "2017", "ends" => "2018"), //set "starts" to the year of autumn term, "ends" to the year of spring and summer term
	"autumn" => array("starts" => "25/09", "ends" => "01/12"), //set "starts" to the date of start of autumn term, in the form of "DD/MM"
	"spring" => array("starts" => "08/01", "ends" => "16/03"), //set "starts" to the date of start of spring term, in the form of "DD/MM"
	"summer" => array("starts" => "16/04", "ends" => "22/06"), //set "starts" to the date of start of summer term, in the form of "DD/MM"
);

?>

<?php
require "recaptcha_autoload.php";
require "config.php";

if ($reCaptchaSiteKey == "" || $reCaptchaSiteSecretKey == "") {
    die("reCaptcha API Key and Secret not found. Register yours at https://www.google.com/recaptcha/ .");
}
$recaptcha = new \ReCaptcha\ReCaptcha($reCaptchaSiteSecretKey);
$lang = 'en';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Goodricke - The Automated Cleaning Reminder Solution</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="./index.html">
                        GNU/Goodricke
                    </a>
                </li>
                <li>
                    <a href="#">Set Reminder</a>
                </li>
                <li>
                    <a href="./term.html">Term Dates</a>
                </li>
                <li>
                    <a href="./about.html">About</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                    <?php 
                    if (!isset($_POST['submit']) || !isset($_POST['g-recaptcha-response'])) {
                        echo '
                        <h1>Setup a Reminder</h1>
                        <br />
                        <p>Setup a weekly email reminder with your York email address below:</p>
                        <br />
                        <form class="form-inline" method="post" name="setupForm" action="';echo $_SERVER["PHP_SELF"];echo '">
                            <div class="form-group">
                                <label class="sr-only" for="emailAddress">Email Address To Receive Alerts</label>
                                <div class="input-group">
                                    <div class="input-group-addon">York Email Address:</div>
                                    <input type="text" class="form-control" name="yorkEmail" id="yorkEmail" placeholder="abc1234">
                                    <div class="input-group-addon">@york.ac.uk</div>
                                </div>
                            </div><br /><br /><br />
                            <div class="form-group">
                                <label>Inform me on the evening of...</label><br />
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="EmailSunday"> Sunday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailMonday"> Monday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailTuesday"> Tuesday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailWednesday"> Wednesday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailThursday"> Thursday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailFriday"> Friday  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailSaturday"> Saturday  
                                    </label>
                                </div><br /><br />
                                <label>During...</label><br />
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="EmailTermTime"> Term Time  
                                    </label>
                                    <label>
                                        <input type="checkbox" id="EmailVacations"> Vacations
                                    </label>
                                </div>
                                <br /><br /><br />
                                <div class="g-recaptcha" data-sitekey="';echo $reCaptchaSiteKey;echo'"></div>
                                <script type="text/javascript"
                                        src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
                                </script>
                                <br /><br />
                                <button type="submit" class="btn btn-primary" name="submit">Set Up Reminder</button>
                            </div>
                        </form>';
                    }

                    else {

                        if ($CDNXForwardedFor == True && isset($_SERVER['X-Forwarded-For'])){
                            $userAddr = $_SERVER['X-Forwarded-For'];
                        }
                        else {
                            $userAddr = $_SERVER['REMOTE_ADDR'];
                        }
                        
                        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $userAddr);

                        $client_email = filter_var($_POST['yorkEmail'],FILTER_SANITIZE_EMAIL) + "@york.ac.uk";

                        if ($resp->isSuccess()) {

                            if (filter_var($client_email, FILTER_VALIDATE_EMAIL)) {

                                $days_array = [
                                    "monday" => 0,
                                    "tuesday" => 0,
                                    "wednesday" => 0,
                                    "thursday" => 0,
                                    "friday" => 0,
                                    "saturday" => 0,
                                    "sunday" => 0,
                                ];

                                $choices_array = [
                                    "termtime" => 0,
                                    "vacations" => 0,
                                ];

                                //Set days according to checkboxes
                                $days_array_empty = true;
                                
                                if (isset($_POST['EmailMonday'])) {
                                    $days_array['monday'] = 1;
                                    $days_array_empty = false;
                                }

                                if (isset($_POST['EmailTuesday'])) {
                                    $days_array['tuesday'] = 1;
                                    $days_array_empty = false;
                                }

                                if (isset($_POST['EmailWednesday'])) {
                                    $days_array['wednesday'] = 1;
                                    $days_array_empty = false;
                                }

                                if (isset($_POST['EmailThursday'])) {
                                    $days_array['thursday'] = 1;
                                    $days_array_empty = false;
                                }
                                
                                if (isset($_POST['EmailFriday'])) {
                                    $days_array['friday'] = 1;
                                    $days_array_empty = false;
                                }
                                
                                if (isset($_POST['EmailSaturday'])) {
                                    $days_array['saturday'] = 1;
                                    $days_array_empty = false;
                                }
                                
                                if (isset($_POST['EmailSunday'])) {
                                    $days_array['sunday'] = 1;
                                    $days_array_empty = false;
                                }

                                //Set termtime and/or vacation reminders according to choice
                                $days_array_empty = true;

                                if (isset($_POST['EmailTermTime'])) {
                                    $choices_array["termtime"] = 1;
                                    $days_array_empty = false;
                                }

                                if (isset($_POST['EmailVacations'])) {
                                    $choices_array["vacations"] = 1;
                                    $days_array_empty = false;
                                }

                                if ($days_array_empty == true || $days_array_empty == true) {
                                    //Empty day selection error
                                    echo '
                                    <h1>Error</h1>
                                    <br />
                                    <p>You don\'t seem to have selected any day (or termtime/vacations) to be reminded. </p><br /><br />
                                    <form action="reminder.php">
                                        <input type="submit" value="Return">
                                    </form>
                                    <br />
                                    ';
                                }

                                else {
                                    //generate unsubscription hash
                                    $unsub_hash = sha1(strrev(base64_encode($client_email)));

                                    //sqlite operations
                                    //Todo: finish this!
                                    $client_submission = new SQLite3('Goodricke.sqlite');
                                    $client_submission_statement = $client_submission->prepare('INSERT INTO subscriptions(senderip, sender, receiver, messagebody, messageIV, messagetag) VALUES (:ip , :sender , :receiver , :body , :iv , :tag);');
                                    $client_submission_statement->bindvalue(':ip',$client_IP);

                                    echo '
                                    <h1>Successful</h1>
                                    <br />
                                    <p>The subscription is now set. You will receive reminders on evening(s) of the day(s) you selected.</p>
                                    <br />
                                    ';
                                }
                            else {
                                //Email address invalid
                                echo '
                                <h1>Error</h1>
                                <br />
                                <p>There seems to be some problem with the email addresss you entered.</p><br /><br />
                                <form action="reminder.php">
                                    <input type="submit" value="Return">
                                </form>
                                <br />
                                ';
                            }

                        }
                        else {
                            //Verification code invalid
                            echo '
                            <h1>Error</h1>
                            <br />
                            <p>There seems to be some problem with the verification code you entered.</p><br /><br />
                            <form action="reminder.php">
                                <input type="submit" value="Return">
                            </form>
                            <br />
                            ';
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>

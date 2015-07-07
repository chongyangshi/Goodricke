<?php
require "utils.php";
require "config.php";
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
                    <a href="./term.php">Term Dates</a>
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

                            //Avoid PHP's notice if no $_GET was supplied.
                            if ((!isset($_GET['e'])) || (!isset($_GET['v']))) {
                                //No GET data supplied.
                                echo '<h1>Error</h1><br />';
                                echo '<p>This unsubscription link may have expired or is invalid.</p>';
                                die();
                            }
                            

                            $email_address = $_GET['e'];
                            $verification_code = $_GET['v'];

                            if ((!check_message_base64($email_address)) || (!check_message_base64($verification_code))) {
                                //Invalid GET data.
                                echo '<h1>Error</h1><br />';
                                echo '<p>This unsubscription link may have expired or is invalid.</p>';
                            }

                            else {

                                $client_email = base64_decode($email_address);
                                $user_hash = sha1(strrev($email_address).$emailHashingSalt);

                                if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
                                    //Email not in a valid form.
                                    echo '<h1>Error</h1><br />';
                                    echo '<p>This unsubscription link may have expired or is invalid.</p>';
                                }

                                else {

                                    if ($user_hash == $verification_code) {
                                        //sqlite operations
                                        $client_submission = new SQLite3('Goodricke.sqlite');
                                        $client_submission_valid = false;

                                        //check if that client email is already in the system
                                        $client_submission_check = $client_submission->prepare('SELECT active FROM subscriptions WHERE (yorkemail = :email);');
                                        $client_submission_check->bindvalue(':email', $client_email);

                                        if ($client_submission_check_execution = $client_submission_check->execute()) {

                                            $client_submission_check_result = $client_submission_check_execution->fetchArray(SQLITE3_ASSOC);
                                            
                                            if (!isset($client_submission_check_result["active"])) {
                                                //No record of the said email address found
                                                echo '<h1>Error</h1><br />';
                                                echo '<p>This unsubscription link may have expired or is invalid.</p>';
                                            }

                                            else {
                                                $client_submission_valid = true;
                                            }
                                        }

                                        if ($client_submission_valid == true) {

                                            $client_submission_set_active = $client_submission->prepare('DELETE FROM subscriptions WHERE (yorkemail = :email) AND (unsubcode = :unsub);');
                                            $client_submission_set_active->bindvalue(':email', $client_email);
                                            $client_submission_set_active->bindvalue(':unsub', $user_hash);

                                            if ($client_submission_set_active->execute()) {
                                                echo '<h1>Success</h1><br />';
                                                echo '<p>Your have now successfully unsubscribed.</p>';
                                                echo '<p>Thank you.</p>';
                                            }

                                            else {
                                                //If database operation failed.
                                                echo "<h1>500: Server Unavailable.</h1>";
                                                header('HTTP/1.0 500 Internal Server Error'); 
                                            }
                                        }
                                    }  
                                    else {
                                        //Encoded email address does not match our records (unsubscribe hash).
                                        echo '<h1>Error</h1><br />';
                                        echo '<p>This unsubscription link may have expired or is invalid.</p>';
                                    }
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

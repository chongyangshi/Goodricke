<?php 
    require 'config.php';
    require 'utils.php';
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
                    <a href="./reminder.php">Set Reminder</a>
                </li>
                <li>
                    <a href="#">Term Dates</a>
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
                        <h1>University of York Term Dates</h1>
                        <br />
                        <div class="col-lg-5">
                            <p>Below is the (undergraduate) term dates as copied from the <a href="https://www.york.ac.uk/about/term-dates/">official source</a>. The official source should be considered authoritative whenever a change takes place. Taught postgraduate term dates may vary.</p>
                            <br />
                            <div class="col-lg-8">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo $termDates['academicYear']['starts']."-".$termDates['academicYear']['ends']; ?></th>
                                            <th>Begins on</th>
                                            <th>Ends on</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Autumn</th>
                                            <th><?php echo read_date($termDates['autumn']['starts'], 0)[0]." ".read_date($termDates['autumn']['starts'], 0)[1]." ".$termDates['academicYear']['starts']; ?></th>
                                            <th><?php echo read_date($termDates['autumn']['ends'], 0)[0]." ".read_date($termDates['autumn']['ends'], 0)[1]." ".$termDates['academicYear']['starts']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Spring</th>
                                            <th><?php echo read_date($termDates['spring']['starts'], 0)[0]." ".read_date($termDates['spring']['starts'], 0)[1]." ".$termDates['academicYear']['ends']; ?></th>
                                            <th><?php echo read_date($termDates['spring']['ends'], 0)[0]." ".read_date($termDates['spring']['ends'], 0)[1]." ".$termDates['academicYear']['ends']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Summer</th>
                                            <th><?php echo read_date($termDates['summer']['starts'], 0)[0]." ".read_date($termDates['summer']['starts'], 0)[1]." ".$termDates['academicYear']['ends']; ?></th>
                                            <th><?php echo read_date($termDates['summer']['ends'], 0)[0]." ".read_date($termDates['summer']['ends'], 0)[1]." ".$termDates['academicYear']['ends']; ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

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

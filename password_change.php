<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
define('tAccess', TRUE);
define('dAccess', TRUE);
include_once('functions.inc.php');
doAuth();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    global $password, $repeat_password;
    $errors = false;
    if($password != $repeat_password) {
        $errors = true;
        $messages["warning"][] = "Password and Confirm Password is not the same";
    }

    if($password == NULL || $password == "") {
        $errors = true;
        $messages["warning"][] = "Password cannot be blank";
    }

    if(!$errors) {
        $db = connectDB();
        $password = htmlspecialchars($password);
        $username = $_SESSION[$mySessionKey]["row_data"]["username"];
        $sql = "UPDATE dlpclienttable SET password='$password' WHERE username='$username'";

        $messages["success"][] = "Password Changed";
        if($stmt = $db->prepare($sql)) {
            $result = $stmt->execute();
        } 
        if(mysqli_connect_errno()) {
            $messages["alert"][] = "User update error " . mysqli_connect_errno();
        }
        $db->close();
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DLPTrade Profile</title>
        <link type="text/css" href="admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="admin/css/theme.css" rel="stylesheet">
        <link type="text/css" href="admin/images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
            rel='stylesheet'>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.php">DLPTrade</a>
                    <div class="nav-collapse collapse navbar-inverse-collapse">
                        <ul class="nav pull-right">
                            <li class="nav-user dropdown"><a href="admin/#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="admin/images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="dashboard.php">Your Profile</a></li>
                                    <li><a href="password_change.php">Change Password</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.nav-collapse -->
                </div>
            </div>
            <!-- /navbar-inner -->
        </div>
        <!-- /navbar -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <div class="sidebar">
                            <ul class="widget widget-menu unstyled">
                                <li class="active"><a href="dashboard.php"><i class="menu-icon icon-dashboard"></i>Dashboard</a></li>
                            </ul>
                            <!--/.widget-nav-->
                            
                            <ul class="widget widget-menu unstyled">
                                <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                        <div class="content">
                            <div class="module">
                                <div class="module-head">
                                    <h3>User Profile</h3>
                                </div>
                                <div class="module-body">
                                    <?php foreach($messages["warning"] as $warning ) { ?>
                                    <div class="alert">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Warning!</strong> <?php echo $warning ?>
                                    </div>
                                    <?php } ?>
                                    <?php foreach($messages["alert"] as $alert ) { ?>
                                    <div class="alert alert-error">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Alert!</strong> <?php echo $alert ?>
                                    </div>
                                    <?php } ?>
                                    <?php foreach($messages["success"] as $success ) { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Success!</strong> <?php echo $success ?>
                                    </div>
                                    <?php } ?>
                                    <br /><!-- spacer -->

                                    <form class="form-horizontal row-fluid" action="password_change.php" method="post">
                                        <?php $values = $_SESSION[$mySessionKey]["row_data"]; ?>
                                        <div class="control-group">
                                            <label class="control-label" for="id_password">Password</label>
                                            <div class="controls">
                                                <input type="password" id="id_password" name="password" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="id_repeat_password">Repeat Password</label>
                                            <div class="controls">
                                                <input type="password" id="id_repeat_password" name="repeat_password" class="span8">
                                            </div>
                                        </div>
                                        <!-- submit button -->
                                        <div class="control-group">
                                            <div class="controls">
                                                <button type="submit" class="btn">Submit Form</button>
                                            </div>
                                        </div>
                                        <!-- submit button -->
                                    </form>
                                </div>
                            </div>
                        <!--/.content-->
                        </div>
                    </div>
                    <!--/.span9-->
                </div>
            </div>
            <!--/.container-->
        </div>
        <!--/.wrapper-->
        <div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2018 DLPTrade.com </b>All rights reserved.
            </div>
        </div>
        <script src="admin/scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="admin/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="admin/scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="admin/scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="admin/scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="admin/scripts/common.js" type="text/javascript"></script>
      
    </body>

<?php
    global $messages; 
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
                                <li class="active"><a href="dashboard.php"><i class="menu-icon icon-dashboard"></i>Dashboard
                                </a></li>
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

                                    <form class="form-horizontal row-fluid" action="dashboard.php" method="post">
                                        <?php $values = $_SESSION[$mySessionKey]["row_data"]; ?>
                                        <input type="hidden" name="action" value="save_profile" />
                                        <input type="hidden" name="username" value="<?php echo $values["username"] ?>" />
                                        <div class="control-group">
                                            <label class="control-label" for="username">Username</label>
                                            <div class="controls">
                                                <input type="text" id="username" placeholder="<?php echo $values["username"] ?>" class="span8" disabled>
                                                <span class="help-inline">Username cannot be changed. Contact administrator to change.</span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="phone_number">Phone Number</label>
                                            <div class="controls">
                                                <input type="text" id="phone_number" name="phone_number" placeholder="<?php echo $values["phone_number"] ?>" value="<?php echo $values["phone_number"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="first_name">First Name</label>
                                            <div class="controls">
                                                <input type="text" id="first_name" name="first_name" placeholder="<?php echo $values["first_name"] ?>" value="<?php echo $values["first_name"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="middle_name">Middle Name</label>
                                            <div class="controls">
                                                <input type="text" id="middle_name" name="middle_name" placeholder="<?php echo $values["middle_name"] ?>" value="<?php echo $values["middle_name"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="last_name">Last Name</label>
                                            <div class="controls">
                                                <input type="text" id="last_name" name="last_name" placeholder="<?php echo $values["last_name"] ?>" value="<?php echo $values["last_name"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="address1">Address</label>
                                            <div class="controls">
                                                <input type="text" id="address1" name="address1" placeholder="<?php echo $values["address1"] ?>" value="<?php echo $values["address1"] ?>" class="span8">
                                                <input type="text" id="address2" name="address2" placeholder="<?php echo $values["address2"] ?>" value="<?php echo $values["address2"] ?>" class="span8">
                                                <input type="text" id="address3" name="address3" placeholder="<?php echo $values["address3"] ?>" value="<?php echo $values["address3"] ?>" class="span8">
                                                <input type="text" id="address4" name="address4" placeholder="<?php echo $values["address4"] ?>" value="<?php echo $values["address4"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="city">City</label>
                                            <div class="controls">
                                                <input type="text" id="city" name="city" placeholder="<?php echo $values["city"] ?>" value="<?php echo $values["city"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="state">State</label>
                                            <div class="controls">
                                                <input type="text" id="state" name="state" placeholder="<?php echo $values["state"] ?>" value="<?php echo $values["state"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="zip_code">Zip Code</label>
                                            <div class="controls">
                                                <input type="text" id="zip_code" name="zip_code" placeholder="<?php echo $values["zip_code"] ?>" value="<?php echo $values["zip_code"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="email">Email</label>
                                            <div class="controls">
                                                <input type="text" id="email" name="email" placeholder="<?php echo $values["email"] ?>" value="<?php echo $values["email"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="occupation">Occupation</label>
                                            <div class="controls">
                                                <input type="text" id="occupation" name="occupation" placeholder="<?php echo $values["occupation"] ?>" value="<?php echo $values["occupation"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="source_of_funds">Source of Funds</label>
                                            <div class="controls">
                                                <input type="text" id="source_of_funds" name="source_of_funds" placeholder="<?php echo $values["source_of_funds"] ?>" value="<?php echo $values["source_of_funds"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="usage_of_funds">Usage of Funds</label>
                                            <div class="controls">
                                                <input type="text" id="usage_of_funds" name="usage_of_funds" placeholder="<?php echo $values["usage_of_funds"] ?>" value="<?php echo $values["usage_of_funds"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="employer">Employer</label>
                                            <div class="controls">
                                                <input type="text" id="employer" name="employer" placeholder="<?php echo $values["employer"] ?>" value="<?php echo $values["employer"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="ss_id_number">SS ID Number</label>
                                            <div class="controls">
                                                <input type="text" id="ss_id_number" name="ss_id_number" placeholder="<?php echo $values["ss_id_number"] ?>" value="<?php echo $values["ss_id_number"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="account_number">Account Number</label>
                                            <div class="controls">
                                                <input type="text" id="account_number" name="account_number" placeholder="<?php echo $values["account_number"] ?>" value="<?php echo $values["account_number"] ?>" class="span8">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="domain">Domain</label>
                                            <div class="controls">
                                                <input type="text" id="domain" name="domain" placeholder="<?php echo $values["domain"] ?>" value="<?php echo $values["domain"] ?>" class="span8">
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

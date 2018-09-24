<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DLPTrade Profile</title>
        <link type="text/css" href="edmin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="edmin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="edmin/css/theme.css" rel="stylesheet">
        <link type="text/css" href="edmin/images/icons/css/font-awesome.css" rel="stylesheet">
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
                            <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="edmin/images/user.png" class="nav-avatar" />
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
                                    <?php if(isset($messages["warning"])) { ?>
                                    <?php foreach($messages["warning"] as $warning ) { ?>
                                    <div class="alert">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Warning!</strong> <?php echo $warning ?>
                                    </div>
                                    <?php } } ?>
                                    <?php if(isset($messages["alert"])) { ?>
                                    <?php foreach($messages["alert"] as $alert ) { ?>
                                    <div class="alert alert-error">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Alert!</strong> <?php echo $alert ?>
                                    </div>
                                    <?php } } ?>
                                    <?php if(isset($messages["success"])) { ?>
                                    <?php foreach($messages["success"] as $success ) { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Success!</strong> <?php echo $success ?>
                                    </div>
                                    <?php } } ?>
                                    <br /><!-- spacer -->

                                    <form class="form-horizontal row-fluid" action="dashboard.php" method="post">
                                        <input type="hidden" name="action" value="save_profile" />
                                        <?php $values = $_SESSION[$mySessionKey]["row_data"]; ?>
                                        <?php foreach($_SESSION[$mySessionKey]["row_data"] as $fieldname => $value): ?>
                                        <?php if(strpos(strtolower($fieldname), 'password') === FALSE): ?>
                                            <div class="control-group">
                                                <label class="control-label" for="id_<?php echo $fieldname ?>"><?php echo titleCase($fieldname) ?></label>
                                                <div class="controls">
                                                    <input type="text" id="id_<?php echo $fieldname ?>" name="<?php echo $fieldname ?>" placeholder="<?php echo titleCase($fieldname); ?>" value="<?php echo $value ?>" class="span8" <?php echo fieldDisabled($fieldname) ?>>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php endforeach ?>
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

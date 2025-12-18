<?php

$url_prefix = URL_PREFIX ?: '';

if (getCurrentUserType() !== 'user') {
    header('Location:' . $url_prefix . '/dashboard/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AH Auth Reseller Panel</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo $url_prefix ?>/assets/css/bootstrap.min.css" />

    <!-- MetisMenu CSS -->
    <link href="<?php echo $url_prefix ?>/assets/js/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $url_prefix ?>/assets/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?php echo $url_prefix ?>/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <script src="<?php echo $url_prefix ?>/assets/js/jquery.min.js" type="text/javascript"></script>

    <style>
        #page-wrapper {
            padding: 1.5rem !important;
        }

        #page-wrapper .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php if (isset($_SESSION['dashboard_user_logged_in']) && $_SESSION['dashboard_user_logged_in'] == true): ?>
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="">AH -RESELLER Panel</a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <!-- /.dropdown -->

                    <!-- /.dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="edit_profile.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <li><a href="edit_main.php?admin_user_id=<?php echo $_SESSION['admin_id']; ?>&operation=edit"><i
                                        class="fa fa-gear fa-fw"></i> Change Password</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                            </li>



                            <ul class="nav nav-second-level">


                            </ul>

                            <li>
                                <a href="transfer_credit.php"><i class="fa fa-signal"></i>Refil Credit</a>
                            </li>

                            <li>
                                <a href="history_refil.php"><i class="fa fa-signal"></i>Unpaid Credit</a>
                            </li>
                            <li>
                                <a href="paid_refil.php"><i class="fa fa-signal"></i>Paid Credit</a>
                            </li>
                            <li>
                                <a href="individual_job.php"><i class="fa fa-signal"></i> Check History</a>
                            </li>



                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>
        <?php endif; ?>
        <!-- The End of the Header -->

        <?php include('updaters.php') ?>
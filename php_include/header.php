<?php
    session_start();
    if(isset($_SESSION['admin']) && isset($_SESSION['admin']['id'])){
    }
    else{
        $success=0;
        //session_destroy();
        $msg="Signed Out! Sign In Again!";
        header("Location: index.php?success=$success&msg=$msg");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    
    <meta http-equiv=”X-UA-Compatible” content=”IE=EmulateIE9”>
    <meta http-equiv=”X-UA-Compatible” content=”IE=9”>


    <link rel="shortcut icon" href="images/top-arrow.png">
    <title>Naseeb| Admin</title>
    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="js/jvector-map/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="css/clndr.css" rel="stylesheet">
    <!--clock css-->
    <link href="js/css3clock/css/style.css" rel="stylesheet">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="js/morris-chart/morris.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/css/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />
       <link rel="stylesheet" type="text/css" href="js/jquery-multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <!-- Just for debugging purposes. Dont actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .form-control {
          color: #201E1E;
        }
    </style>
    </head>
	<body>
	<section id="container">
	<!--header start-->
	<header class="header fixed-top clearfix">
	<!--logo start-->
	<div class="brand">

	    <a href="index.php" class="logo">
	      <p style="color:white;">Naseeb Admin</p>
	    </a>
	    <div class="sidebar-toggle-box">
	        <div class="fa fa-bars"></div>
	    </div>
	</div>
	<!--logo end-->
	<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">

        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="images/male.png">
                <span class="username">Admin</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
               <li><a href="eventHandler.php?event=signout"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
       
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->

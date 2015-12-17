<?php
session_start();
include '../phpInclude/dbconnection.php';
if(isset($_SESSION['id']))
 { 
if($_SESSION['id'])
{
$sql="SELECT u.id as user_id,u.full_name,u.profile_pic,u.email_id,u.latitude,u.longitude,(SELECT count(id) FROM post WHERE user_id=u.id) as total_posts,
(SELECT count(id) FROM hangout WHERE user_id=u.id) as total_hangouts,(SELECT count(id) FROM followers WHERE user_id1=u.id) as followers,(SELECT count(id) FROM followers WHERE user_id2=u.id) as following FROM  user u";
$stmt=$conn->prepare($sql);
$stmt->bindValue(':cuisine_name',$cuisine_name);
$stmt->bindValue(':cuisine_image',$url);

try{
  $stmt->execute();
  $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
}

catch(Exception $e){
    //echo $e->getMessage();
 }
//print_r($result);die;
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


    <link rel="shortcut icon" href="images/favicon.png">
    <title>FoodiAdmin</title>
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
    <!-- Just for debugging purposes. Dont actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    </head>
	<body>
	<section id="container">
	<!--header start-->
	<header class="header fixed-top clearfix">
	<!--logo start-->
	<div class="brand">

	    <a href="index.php" class="logo">
	      <p style="color:white;">  Foodi Admin</p>
	    </a>
	    <div class="sidebar-toggle-box">
	        <div class="fa fa-bars"></div>
	    </div>
	</div>
	<!--logo end-->
	<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
    <!--<li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="images/male.png">
                <span class="username">Admin</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
               <li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
       
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-cutlery"></i>
                        <span>Cuisine</span>
                    </a>
                    <ul class="sub">
                        <li><a href="add_cuisine.php"><i class="fa fa-plus"></i>
                        <span>Add Cuisine</span></a></li>
                        <li><a href="list_cuisine.php"><i class="fa fa-bars"></i>
                        <span>List Cuisine</span></a></li>
                        
                    </ul>
                </li>

                <li class="sub-menu">
                    <a class="active" href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub">
                        <li><a class="active" href="list_user.php"><i class="fa fa-bars"></i>
                        <span>List Users</span></a></li>
                        
                    </ul>
                </li>
               
            </ul>            
          </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
<section class="wrapper">

<div class="row">
                <?php foreach ($result as $key => $value) 
                   { $check=$value['profile_pic'];
                   
                    ?>
                
                    <div class="col-md-6">
                        <!--widget start-->
                        <aside class="profile-nav alt">
                            <section class="panel">
                                <div class="user-heading alt gray-bg">
                                    <a href="#">
                                    <?php 
                                     if(!$check){
                                        ?>
                                        <img alt="" src="images/male.png">
                                    <?php
                                    }
                                    else
                                        {
                                            ?>
                                    <img alt="" src="../uploads/<?php echo $value['profile_pic']; ?>">
                                    <?php 
                                    }
                                    ?>
                                    </a>
                                    <h1><?php echo $value['full_name']; ?></h1>
                                    <p><?php echo $value['email_id']; ?></p>
                                    <p><?php $latitude=$value['latitude'];
                                    $longitude=$value['longitude'];
                                    $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false');
                                
                                    $output= json_decode($geocode);
                                    $data=$output->results;
                                    $ab=$data[0];
                                    $address=($ab->formatted_address)?($ab->formatted_address):"";
                                    echo $address; ?></p>
                                </div>

                                <ul class="nav nav-pills nav-stacked">
                                    <li><a href="user_posts.php?id=<?php echo $value['user_id']; ?>"> <i class="fa fa-envelope-o"></i> Total Posts <span class="badge label-success pull-right r-activity"><?php echo $value['total_posts']; ?></span></a></li>
                                    <li><a href="user_followers.php?id=<?php echo $value['user_id']; ?>"> <i class="fa fa-users"></i> Followers <span class="badge label-danger pull-right r-activity"><?php echo $value['followers']; ?></span></a></li>
                                    <li><a href="user_following.php?id=<?php echo $value['user_id']; ?>"> <i class="fa fa-users"></i> Following <span class="badge label-success pull-right r-activity"><?php echo $value['following']; ?></span></a></li>
                                    <li><a href="user_hangouts.php?id=<?php echo $value['user_id']; ?>"> <i class="fa fa-comments-o"></i> Total Hangouts <span class="badge label-warning pull-right r-activity"><?php echo $value['total_hangouts']; ?></span></a></li>
                                </ul>

                            </section>
                        </aside>
                        <!--widget end-->

                    </div>
                    <?php 
                }
                    ?>
                </div>

</section>
</section>
<!--main content end-->

</section>
<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="js/skycons/skycons.js"></script>
<script src="js/jquery.scrollTo/jquery.scrollTo.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="js/calendar/clndr.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<script src="js/calendar/moment-2.2.1.js"></script>
<script src="js/evnt.calendar.init.js"></script>
<script src="js/jvector-map/jquery-jvectormap-1.2.2.min.js"></script>
<script src="js/jvector-map/jquery-jvectormap-us-lcc-en.js"></script>
<script src="js/gauge/gauge.js"></script>
<!--clock init-->
<script src="js/css3clock/js/css3clock.js"></script>

<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<!--script for this page-->
</body>
</html>

<?php
}
}
else
{
	header("location:index.php");
}
?>  	
<?php
session_start();
include '../phpInclude/dbconnection.php';
if(isset($_SESSION['id']))
 { 
if($_SESSION['id'])
{
$user_id = $_REQUEST['id'];

$sql="SELECT p.id,p.user_id,(SELECT full_name FROM user WHERE id=:user_id) as full_name,p.image,p.description,p.cuisine_id,p.type_id,p.location,p.created_on,(SELECT type.name FROM type WHERE type.id=p.type_id) as type_name,(SELECT cuisine.image FROM cuisine WHERE cuisine.id=p.cuisine_id) as cuisine_image FROM post p WHERE user_id=:user_id";
$stmt=$conn->prepare($sql);
$stmt->bindValue(':user_id',$user_id);

try{
  $stmt->execute();
  $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
}

catch(Exception $e){
    //echo $e->getMessage();
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Posts</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/bootstrap-switch.css" />
    <!--clock css-->
    <link href="js/css3clock/css/style.css" rel="stylesheet">
    <!--jQuery Vdo and Audio Player-->
    <link rel="stylesheet" type="text/css" href="js/jplayer/skin/blue.monday/jplayer.blue.monday.css"/>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>
        .random:before{
          width: 50%;
          height: 136%;
          content: "";
          -ms-transform: rotate(7deg);
          /* -webkit-transform: rotate(7deg); */
          transform: rotate(-45deg);
          background: rgba(255, 255, 255, 1);
          z-index: 2;
          display: block;
          position: absolute;
          left: 5px;
          top: 0px;
          overflow: hidden;
        }

        .random:after{

            width: 50%;
          height: 50%;
          content: "";
          background: white;
          position: absolute;
          right: 40px;
          z-index: 200;
          top: 40px;
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
<center><h3 style="color: #1FB5AD;
  font-family: sans-serif;
  font-size: xx-large;"><?php echo $result[0]['full_name']; ?>'s Posts </h3></center>
<div class="row">

<?php foreach ($result as $key => $value) {
   ?>
<div class="col-md-4">
                <div class="feed-box text-center">
                    <section class="panel">
                        <div class="panel-body">
                            <div class="corner-ribon blue-ribon" >
                            <div class="random">
                                <img alt="" src="../uploads/<?php echo $value['cuisine_image']; ?>" style="  border-radius: 0;height: 71px;width: 71px;">
                            </div>
                            </div>
                            <a href="#">
                                <img alt="" src="../uploads/<?php echo $value['image']; ?>">
                            </a>
                            <h1><?php echo $value['type_name']; ?></h1>
                            <p><?php echo $value['description']; ?></p>
                            <p><?php echo $value['location']; ?></p>
                        </div>
                    </section>
                </div>

            </div>
<?php } ?>            
</div>
</section>
</section>
<!--main content end-->

</section>
<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/bootstrap-switch.js"></script>

<!-- jQuery audio VDO player  -->
<script type="text/javascript" src="js/jplayer/js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="js/jplayer/jplayer.init.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script>

<script src="js/jquery.customSelect.min.js" ></script>


<!--common script init for all pages-->
<script src="js/scripts.js"></script>

<!--toggle initialization-->
<script src="js/toggle-init.js"></script>

<!--clock init-->
<script src="js/css3clock/js/css3clock.js"></script>

<script type="text/javascript">
    //custom select box

    $(function(){
        $('select.styled').customSelect();
    });
</script>


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
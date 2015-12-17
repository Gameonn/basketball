<?php 
require_once('../php_include/db_connection.php');
require_once("../php_include/header.php");
require_once("../php_include/sidebar.php");
require_once("GeneralFunctions.php");


?>
	
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
<section class="wrapper">

<div class="row">
    <div class="col-md-6">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-list-alt"></i></span>
            <div class="mini-stat-info">
                <span><?php 
                $total_questions=count(GeneralFunctions::getAllQuestions());
                echo $total_questions; ?></span>
               Profession
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon tar"><i class="fa fa-list-alt"></i></span>
            <div class="mini-stat-info">
                <span><?php 
                $categories=count(GeneralFunctions::getAllCategories());
                echo $categories; ?></span>
               Education
            </div>
        </div>
    </div>
  </div>
<!--<div class="row">
<div class="col-md-4">
        <div class="profile-nav alt">
            <section class="panel">
                <div class="user-heading alt clock-row terques-bg">
                    <h1><?php echo date("F");?> <?php echo date("d");?></h1>
                    <p class="text-left"><?php echo date("Y");?>, <?php echo date("l");?></p>
                    <p class="text-left"><?php echo date("g:i a");?></p>
                </div>
                <ul id="clock">
                    <li id="sec" style="-webkit-transform: rotate(246deg);"></li>
                    <li id="hour" style="-webkit-transform: rotate(461.5deg);"></li>
                    <li id="min" style="-webkit-transform: rotate(138deg);"></li>
                </ul>

            </section>

        </div>
    </div>

<div class="col-md-8 calendar-block">
 <div class="cal1 ">
 <div class="clndr">
 <div class="clndr-controls">
 <div class="clndr-control-button">
 <span class="clndr-previous-button">
 <i class="fa fa-chevron-left"></i>
 </span>
 </div>
 <div class="month">September 2016</div>
 <div class="clndr-control-button leftalign">
 <span class="clndr-next-button"><i class="fa fa-chevron-right"></i></span>
 </div>
 </div>
 <table class="clndr-table" border="0" cellspacing="0" cellpadding="0">
 <thead><tr class="header-days">
 <td class="header-day">S</td>
 <td class="header-day">M</td>
 <td class="header-day">T</td>
 <td class="header-day">W</td>
 <td class="header-day">T</td>
 <td class="header-day">F</td>
 <td class="header-day">S</td>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="day adjacent-month last-month calendar-day-2016-08-28">
 <div class="day-contents">28</div></td>
 <td class="day adjacent-month last-month calendar-day-2016-08-29">
 <div class="day-contents">29</div></td>
 <td class="day adjacent-month last-month calendar-day-2016-08-30">
 <div class="day-contents">30</div></td>
 <td class="day adjacent-month last-month calendar-day-2016-08-31">
 <div class="day-contents">31</div></td>
 <td class="day calendar-day-2016-09-01">
 <div class="day-contents">1</div></td>
 <td class="day calendar-day-2016-09-02">
 <div class="day-contents">2</div></td>
 <td class="day calendar-day-2016-09-03">
 <div class="day-contents">3</div></td>
 </tr>
 <tr>
 <td class="day calendar-day-2016-09-04">
 <div class="day-contents">4</div></td>
 <td class="day calendar-day-2016-09-05">
 <div class="day-contents">5</div></td>
 <td class="day calendar-day-2016-09-06">
 <div class="day-contents">6</div></td>
 <td class="day calendar-day-2016-09-07">
 <div class="day-contents">7</div></td>
 <td class="day calendar-day-2016-09-08">
 <div class="day-contents">8</div></td>
 <td class="day calendar-day-2016-09-09">
 <div class="day-contents">9</div></td>
 <td class="day calendar-day-2016-09-10">
 <div class="day-contents">10</div></td>
 </tr>
 <tr>
 <td class="day calendar-day-2016-09-11">
 <div class="day-contents">11</div></td>
 <td class="day calendar-day-2016-09-12">
 <div class="day-contents">12</div></td>
 <td class="day calendar-day-2016-09-13">
 <div class="day-contents">13</div></td>
 <td class="day calendar-day-2016-09-14">
 <div class="day-contents">14</div></td>
 <td class="day calendar-day-2016-09-15">
 <div class="day-contents">15</div></td>
 <td class="day calendar-day-2016-09-16">
 <div class="day-contents">16</div></td>
 <td class="day calendar-day-2016-09-17">
 <div class="day-contents">17</div></td>
 </tr>
 <tr>
 <td class="day calendar-day-2016-09-18">
 <div class="day-contents">18</div></td>
 <td class="day calendar-day-2016-09-19">
 <div class="day-contents">19</div></td>
 <td class="day calendar-day-2016-09-20">
 <div class="day-contents">20</div></td>
 <td class="day calendar-day-2016-09-21">
 <div class="day-contents">21</div></td>
 <td class="day calendar-day-2016-09-22">
 <div class="day-contents">22</div></td>
 <td class="day calendar-day-2016-09-23">
 <div class="day-contents">23</div></td>
 <td class="day calendar-day-2016-09-24">
 <div class="day-contents">24</div></td>
 </tr>
 <tr>
 <td class="day calendar-day-2016-09-25">
 <div class="day-contents">25</div>
 </td>
 <td class="day calendar-day-2016-09-26">
 <div class="day-contents">26</div>
 </td>
 <td class="day calendar-day-2016-09-27">
 <div class="day-contents">27</div></td>
 <td class="day calendar-day-2016-09-28">
 <div class="day-contents">28</div></td>
 <td class="day calendar-day-2016-09-29">
 <div class="day-contents">29</div></td>
 <td class="day calendar-day-2016-09-30">
 <div class="day-contents">30</div></td>
 <td class="day adjacent-month next-month calendar-day-2016-10-01">
 <div class="day-contents">1</div></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>
 </div>

</div>  -->
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
	
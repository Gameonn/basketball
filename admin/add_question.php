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

<section class="panel">
                    <header class="panel-heading">
                        Add Question
                         
                    </header>
                    <div class="panel-body">
                        <form action="eventHandler.php" class="form-horizontal " method="POST" enctype='multipart/form-data'>
                        <div class="form-group">
                        <label class="col-sm-3 control-label">Question Title</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control round-input" name="title">
							 <input type="hidden" value="add-question" name="event">
                             <input type="hidden" value="questions.php" name="redirect">
                        </div>
                    </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Select Category</label>
                                <div class="col-sm-6">
                                    <select name="category" id="source" class="form-control round-input">
                                        <optgroup label="Category">
                                        <?php
											$result=GeneralFunctions::getAllCategories();
											foreach ($result as $key => $value) { ?>
                                                                              
                                            <option value="<?php echo $value['id']; ?>" name="category_name"><?php echo $value['category_name']; ?> </option>
                                            <?php } ?>
                                        </optgroup>
                                        
                                        
                                    </select>
                                   
                                </div>
                            </div>
                            <div class="col-sm-12">
                          <center><button type="submit" class="btn btn-theme" style="background: #1fb5ad; color: aliceblue;">Submit</button></center>
                          </div>

                        </form>
                    </div>
                </section>
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
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
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
  	
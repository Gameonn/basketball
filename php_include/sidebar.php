<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li <?php if(stripos($_SERVER['REQUEST_URI'],"dashboard.php")) echo 'class="active"'; ?>>
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                  <li class="sub-menu">
                    <a <?php if(stripos($_SERVER['REQUEST_URI'],"category.php") || stripos($_SERVER['REQUEST_URI'],"add_category.php")) echo 'class="active"'; ?> href="javascript:;">
                        <i class="fa fa-list-alt"></i>
                        <span>Category</span>
                    </a>
                    <!--<ul class="sub">
                       <!-- <li><a <?php if(stripos($_SERVER['REQUEST_URI'],"add_category.php")) echo 'class="active"'; ?> href="add_category.php"><i class="fa fa-plus"></i>
                        <span>Add Category</span></a></li> -->
                     <!--   <li><a <?php if(stripos($_SERVER['REQUEST_URI'],"category.php")) echo 'class="active"'; ?> href="category.php"><i class="fa fa-bars"></i>
                        <span>List Category</span></a></li>
                        
                    </ul>-->
                </li>
				
				  <li class="sub-menu">
                    <a <?php if(stripos($_SERVER['REQUEST_URI'],"questions.php") || stripos($_SERVER['REQUEST_URI'],"add_question.php")) echo 'class="active"'; ?> href="javascript:;">
                        <i class="fa fa-list-alt"></i>
                        <span>Listing</span>
                    </a>
                  <!--  <ul class="sub">
                        <li><a <?php if(stripos($_SERVER['REQUEST_URI'],"add_question.php")) echo 'class="active"'; ?> href="add_question.php"><i class="fa fa-plus"></i>
                        <span>Add Question</span></a></li>
                        <li><a <?php if(stripos($_SERVER['REQUEST_URI'],"questions.php")) echo 'class="active"'; ?> href="questions.php"><i class="fa fa-bars"></i>
                        <span>List Questions</span></a></li>
                        
                    </ul>-->
                </li>
            </ul>            
          </div>
          </div>
          </aside>
        
        <!-- sidebar menu end-->
   
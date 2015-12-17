<?php 
session_start();
require_once '../php_include/db_connection.php';
require_once("GeneralFunctions.php");
$id=$_POST['vid'];

$sql="SELECT category_question.id,category_question.title,category.category_name,category.category_image from category_question join category on category.id=category_question.category_id WHERE category_question.id =:id";
$stmt=$conn->prepare($sql);
$stmt->bindValue(':id',$id);
$stmt->execute();
$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form action="eventHandler.php" class="form-horizontal " method="POST" enctype='multipart/form-data'>
 <input type="hidden" value="edit-question" name="event">
 <input type="hidden" value="questions.php" name="redirect">
 <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>" >
<div class="col-md-5 img-modal form-group" style="margin-left: 10px;">
    <div class="fileupload fileupload-new" data-provides="fileupload">
<div class="fileupload-new thumbnail" style="width: 200px;">
    <img src="../uploads/<?php if($result[0]['category_image']) echo $result[0]['category_image']; else echo 'default_category.jpg'; ?>" alt="" />
</div>

</div></div>
<div class="col-md-7">
<div class="form-group">
        <label> Category Name</label>
        <input id="name" value="<?php echo $result[0]['category_name'];?>" class="form-control" name="category_name" readonly>
    </div>

    <div class="form-group">
        <label> Question Title</label>
        <input id="title" value="<?php echo $result[0]['title'];?>" class="form-control" name="title">
    </div>
    
    <div class="pull-right">
       <!-- <button class="btn btn-danger" type="submit" name="save_change" value="0">Delete</button>-->
        <button type="submit" class="btn btn-primary" name="save_change" value="1">Save changes</button>
    </div>
</div>
</form>

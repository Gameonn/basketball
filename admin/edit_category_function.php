<?php 
session_start();
require_once '../php_include/db_connection.php';
$id=$_POST['vid'];

$sql="SELECT * from category where category.id=:id";
$stmt=$conn->prepare($sql);
$stmt->bindValue(':id',$id);
$stmt->execute();
$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form action="eventHandler.php" class="form-horizontal " method="POST" enctype='multipart/form-data'>
 <input type="hidden" value="edit-category" name="event">
  <input type="hidden" value="category.php" name="redirect">
 <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>" >
<div class="col-md-5 img-modal form-group" style="margin-left: 10px;">
    <div class="fileupload fileupload-new" data-provides="fileupload">
<div class="fileupload-new thumbnail" style="width: 200px;">
    <img src="../uploads/<?php if($result[0]['category_image']) echo $result[0]['category_image']; else echo 'default_category.jpg'; ?>" alt="" />
</div>
<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
<div>
           <span class="btn btn-white btn-file" style="background: #1fb5ad; color: aliceblue;">
           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
           <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
           <input type="file" class="default" name="category_image" />
           </span>
</div>
</div></div>
<div class="col-md-7">
<div class="form-group">
        <label> Category Name</label>
        <input id="name" value="<?php echo $result[0]['category_name'];?>" class="form-control" name="category_name">
    </div>

    <div class="pull-right">
       <!-- <button class="btn btn-danger" type="submit" name="save_change" value="0">Delete</button>-->
        <button type="submit" class="btn btn-primary" name="save_change" value="1">Save changes</button>
    </div>
</div>
</form>

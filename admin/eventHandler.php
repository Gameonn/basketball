<?php 
//this page is to handle all the admin events occured at client side
 require_once("../php_include/db_connection.php"); 
//require_once('PHPMailer_5.2.4/class.phpmailer.php');

/* ******         

BASIC FUNCTIONS USED
*****
*/
function randomFileNameGenerator($prefix){
  $r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
  if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
  else return $r;
}

  function sendEmail($email,$subjectMail,$bodyMail,$email_back){

    $mail = new PHPMailer(true); 
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
      //$mail->Host       = SMTP_HOST; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->Host       = SMTP_HOST; // sets the SMTP server
      $mail->Port       = SMTP_PORT;                    // set the SMTP port for the GMAIL server
      $mail->Username   = SMTP_USER; // SMTP account username
      $mail->Password   = SMTP_PASSWORD;        // SMTP account password
      $mail->AddAddress($email, '');     // SMTP account password
      $mail->SetFrom(SMTP_EMAIL, SMTP_NAME);
      $mail->AddReplyTo($email_back, SMTP_NAME);
      $mail->Subject = $subjectMail;
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';  // optional - MsgHTML will create an alternate automaticall//y
      $mail->MsgHTML($bodyMail) ;
      if(!$mail->Send()){
        $success='0';
        $msg="Error in sending mail";
      }else{
        $success='1';
      }
    } catch (phpmailerException $e) {
      $msg=$e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      $msg=$e->getMessage(); //Boring error messages from anything else!
    }
    //echo $msg;
  }
 
    function generateRandomString($length = 6){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
    
/* ***********

END OF FUNCTIONS DECLARATION
***********
*/

/*
******
START OF CASES-- MAIN FUNCTIONALITY
******
*/
  $success=0;
  $msg="";
  session_start();
  //switch case to handle different events
  switch($_REQUEST['event']){


  case "signin":    
    $success=0;
    $user=$_REQUEST['email'];
    $password=$_REQUEST['password'];
    $redirect=$_REQUEST['redirect'];
    $sth=$conn->prepare("select * from admin where email=:email or username=:email");
    $sth->bindValue("email",$user);
    try{$sth->execute();}
    catch(Exception $e){}
    $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    
    
    if(count($result)){
      foreach($result as $row){
    
        if($row['password']==md5($password)){
          session_start();
          $success=1;
          
          $_SESSION['admin']['id']=$row['id'];
          $_SESSION['admin']['email']=$row['email'];
          
        }
      }
    }
    if(!$success){
      $redirect="index.php";
      $msg="Invalid Username/Password";
    }
    header("Location: $redirect?success=$success&msg=$msg");
    break;
    
  case "add-profession":
    
    $profession=$_REQUEST['profession'];
    $redirect=$_REQUEST['redirect'];
    
    $sql="Insert into profession(`id`,`profession_status`,`created_on`) values(DEFAULT,:profession,NOW()) ";
    $sth=$conn->prepare($sql);
    $sth->bindValue('profession',$profession);
    try{
	$sth->execute();
	$success=1;
	$msg="Profession Added";
	}
    catch(Exception $e){}
    
    header("Location: $redirect?success=$success&msg=$msg");
  break;

   case "add-relationship":
    
    $rel_status=$_REQUEST['rel_status'];
    $redirect=$_REQUEST['redirect'];
    
    $sql="Insert into relation(`id`,`rel_status`,`created_on`) values(DEFAULT,:rel_status,NOW()) ";
    $sth=$conn->prepare($sql);
    $sth->bindValue('rel_status',$rel_status);
    try{
	$sth->execute();
	$success=1;
	$msg="Relationship Option Added";
	}
    catch(Exception $e){}
    
    header("Location: $redirect?success=$success&msg=$msg");
  break;
  
     case "add-education":
    
    $education=$_REQUEST['education_level'];
    $redirect=$_REQUEST['redirect'];
    
    $sql="Insert into relation(`id`,`education_level`,`created_on`) values(DEFAULT,:education_level,NOW()) ";
    $sth=$conn->prepare($sql);
    $sth->bindValue('education_level',$education);
    try{
	$sth->execute();
	$success=1;
	$msg="Education Level Added";
	}
    catch(Exception $e){}
    
    header("Location: $redirect?success=$success&msg=$msg");
  break;
  
	   case "add-religion":
    
    $religion=$_REQUEST['religion'];
    $redirect=$_REQUEST['redirect'];
    
    $sql="Insert into religion(`id`,`religion_status`,`created_on`) values(DEFAULT,:rel_status,NOW()) ";
    $sth=$conn->prepare($sql);
    $sth->bindValue('rel_status',$religion);
    try{
	$sth->execute();
	$success=1;
	$msg="Religion Option Added";
	}
    catch(Exception $e){}
    
    header("Location: $redirect?success=$success&msg=$msg");
  break;
  
    case "add-category":
    
     $image=$_FILES['category_image'];
    $category=$_REQUEST['category_name'];
    $redirect=$_REQUEST['redirect'];
    
	if($image){
	 $randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
    if(@move_uploaded_file($image['tmp_name'], "../uploads/$randomFileName")){
    $image_path=$randomFileName;
    }
	}
	else{
	$image_path="default_category.jpg";
	}
	
    $sql="Insert into category(`id`,`category_name`,`category_image`,`created_on`) values(DEFAULT,:category_name,:category_image,NOW()) ";
    $sth=$conn->prepare($sql);
    $sth->bindValue('category_name',$category);
    $sth->bindValue('category_image',$image_path);
    try{$sth->execute();
      $success=1;
      $msg="Category Added";}
    catch(Exception $e){}
    
    header("Location: $redirect?success=$success&msg=$msg");
  break;

  case "edit-category":
  
  $category_id=$_REQUEST['id'];
  $category_name=$_REQUEST['category_name'];
  $image=$_FILES['category_image'];
  $redirect=$_REQUEST['redirect'];
  
  if($image){
	 $randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$image['name']));
    if(@move_uploaded_file($image['tmp_name'], "../uploads/$randomFileName")){
    $image_path=$randomFileName;
    }
	}
	else{
	$image_path="";
	}
	
	if($image_path)
	$sql="update category set category_image=:category_image,category_name=:category_name where id=:id";
	else
	$sql="update category set category_name=:category_name where id=:id";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('category_name',$category_name);
	$sth->bindValue('id',$category_id);
	if($image_path) $sth->bindValue('category_image',$image_path);
	try{$sth->execute();
	$success='1';
	$msg="Category Updated";
	}
	catch(Exception $e){}
	
	header("Location: $redirect?success=$success&msg=$msg");
  break;
  
  
  case "edit-question":
  
  $id=$_REQUEST['id'];
  $category_id=$_REQUEST['category_id'];
  $title=$_REQUEST['title'];
  $redirect=$_REQUEST['redirect'];
  
	$sql="update category_question set title=:title where id=:id";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$id);
	//$sth->bindValue('category_id',$category_id);
	$sth->bindValue('title',$title);
	try{$sth->execute();
	$success='1';
	$msg="Question Updated";
	}
	catch(Exception $e){}
	
	header("Location: $redirect?success=$success&msg=$msg");
  break;
  
   
  
  case "signout":

  session_start();
    unset($_SESSION);
    session_destroy();
    header("Location: index.php?success=1&msg=Signout Successful!");
  break;
    
} 
?>
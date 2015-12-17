<?php
//this is an api to recover password
// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+

require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");
require_once('../PHPMailer_5.2.4/class.phpmailer.php');


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
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automaticall//y
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




$success=$msg="0";$data=array();

// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$email=$_REQUEST['email'];

if(!($email)){
	$success="0";
	$msg="Incomplete Parameters";
}
else{
	$sql="SELECT * from users where email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue("email",$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$user=$res[0]['first_name'];
	$username=$user?$user:'User';
	if(count($res)){
	$token=GeneralFunctions::generateRandomString();
	$password=GeneralFunctions::generateRandomString(6);
	
	$sql="UPDATE users set token=:token,password=:password where email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue("email",$email);
	$sth->bindValue("token",md5($token));
	$sth->bindValue('password',md5($password));
	$count=0;
	try{$count=$sth->execute();}
	catch(Exception $e){}
		if($count){
			$success="1";
			$msg="Email successfully sent.";
			
			$body_email="<div style='font-size:16px;line-height:1.4;'>
						<p> Dear {$username} </p>	
						<p>We have received your password reset request.</p>
						<p>Your Password is reset as per your request:</p>
						<p>New Password: {$password}</p>
						<p>Have a good NBA experience.</p>
						<br>
						<p>Best,</p>
						<p>NBA Users</p>
						</div>";
								
			sendEmail($email,"NBA- Recover Password",$body_email,SMTP_EMAIL);
		}
		else{
			$success="0";
			$msg="Error occurred";
		}
	}
	else{
			$success="0";
			$msg="Email not Correct";
	}
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>
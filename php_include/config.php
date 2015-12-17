<?php
//error_reporting(0);
$servername = $_SERVER['HTTP_HOST'];
$pathimg=$servername."/";
define("ROOT_PATH",$_SERVER['DOCUMENT_ROOT']);
define("UPLOAD_PATH","http://code-brew.com/projects/basketball/");
define("BASE_PATH","http://code-brew.com/projects/basketball/");

define("SERVER_OFFSET","21600");
$DB_HOST = 'localhost';
$DB_DATABASE = 'codebrew_basketball';
$DB_USER = 'codebrew_super';
$DB_PASSWORD = 'core2duo';

define("AUTH_KEY","AIzaSyBJuGr0iz5n7XSUkBPOYzVT8LqivIwVo_Y");

define('SMTP_USER','pargat@code-brew.com');
define('SMTP_EMAIL','pargat@code-brew.com');
define('SMTP_PASSWORD','core2duo');
define('SMTP_NAME','Naseeb');
define('SMTP_HOST','mail.code-brew.com');
define('SMTP_PORT','25');
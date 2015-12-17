<?php
class GeneralFunctions
{
	public static function getImagePath($file_name){
		if(!empty($file_name))
		{
			return BASE_PATH."uploads/".$file_name;
				//return BASE_PATH."timthumb.php?src=uploads/".$file_name;
			}
			else
			{
				return BASE_PATH."uploads/default_256.png";
				//return BASE_PATH."timthumb.php?src=uploads/default_256.png";
				
			}
		}

	
	public static function getBasePath(){
	return BASE_PATH."/timthumb.php?src=uploads/";
	}
	
	public static function getAllQuestions(){
	global $conn;
	
	$sql="SELECT category_question.id,category_question.title,category.category_name,category.category_image from category_question join category on category.id=category_question.category_id";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getAllCategories(){
	global $conn;
	
	$sql="SELECT * from category";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getCategoriescount(){
	global $conn;
	
	$sql="SELECT count(id) from category";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function generateRandomString($length = 10){
		$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++){
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
}
?>
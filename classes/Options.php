<?php
class Options
{

	public static function getDatingPref(){
	global $conn;
	
	$sql="select id,d_preference as value from dating_pref";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getDatingPrefId($val){
	global $conn;
	
	$sql="select id from dating_pref where d_preference=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setDatingPrefId($val){
	global $conn;
	
	$id=self::getDatingPrefId($val);
	if(!$id){
		$sql="INSERT into dating_pref(id,d_preference,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getDrinkingOption(){
	global $conn;
	
	$sql="select id,drinking_status as value from drinking";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getDrinkingOptionId($val){
	global $conn;
	
	$sql="select id from drinking where drinking_status=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setDrinkingOptionId($val){
	global $conn;
	
	$id=self::getDrinkingOptionId($val);
	if(!$id){
		$sql="INSERT into drinking(id,drinking_status,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getEducationOption(){
	global $conn;
	
	$sql="select id,education_level as value from education";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getEducationOptionId($val){
	global $conn;
	
	$sql="select id from education where education_level=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setEducationOptionId($val){
	global $conn;
	
	$id=self::getEducationOptionId($val);
	if(!$id){
		$sql="INSERT into education(id,education_level,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getFoodPref(){
	global $conn;
	
	$sql="select id,f_preference as value from food_pref";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getFoodPrefId($val){
	global $conn;
	
	$sql="select id from food_pref where f_preference=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setFoodPrefId($val){
	global $conn;
	
	$id=self::getFoodPrefId($val);
	if(!$id){
		$sql="INSERT into food_pref(id,f_preference,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getProfessionOption(){
	global $conn;
	
	$sql="select id,profession_status as value from profession";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getProfessionOptionId($val){
	global $conn;
	
	$sql="select id from profession where profession_status=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setProfessionOptionId($val){
	global $conn;
	
	$id=self::getProfessionOptionId($val);
	if(!$id){
		$sql="INSERT into profession(id,profession_status,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getRelationOption(){
	global $conn;
	
	$sql="select id,rel_status as value from relation";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getRelationOptionId($val){
	global $conn;
	
	$sql="select id from relation where rel_status=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setRelationOptionId($val){
	global $conn;
	
	$id=self::getRelationOptionId($val);
	if(!$id){
		$sql="INSERT into relation(id,rel_status,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getReligionOption(){
	global $conn;
	
	$sql="select id,religion_status as value from religion";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getReligionOptionId($val){
	global $conn;
	
	$sql="select id from religion where religion_status=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setReligionOptionId($val){
	global $conn;
	
	$id=self::getDatingPrefId($val);
	if(!$id){
		$sql="INSERT into religion(id,religion_status,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getSexualPref(){
	global $conn;
	
	$sql="select id,s_preference as value from sexual_pref";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getSexualPrefId($val){
	global $conn;
	
	$sql="select id from sexual_pref where s_preference=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setSexualPrefId($val){
	global $conn;
	
	$id=self::getSexualPrefId($val);
	if(!$id){
		$sql="INSERT into sexual_pref(id,s_preference,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
	public static function getHeight(){
	global $conn;
	
	$sql="select height_preference as id,height_preference as value from height";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getAge(){
	global $conn;
	
	$sql="select age_preference as id,age_preference as value from age";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getCity(){
	global $conn;
	
	$sql="select city_preference as id,city_preference as value from city";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getGender(){
	global $conn;
	
	$sql="select gender_preference as id,gender_preference as value from gender";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getSmokingOption(){
	global $conn;
	
	$sql="select id,smoking_status as value from smoking";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	return $result;
	}
	
	public static function getSmokingOptionId($val){
	global $conn;
	
	$sql="select id from smoking where smoking_status=:val";
	$sth=$conn->prepare($sql);
	$sth->bindValue('val',$val);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	$id=$result[0]['id'];
	return $id;
	}
	
	public static function setSmokingOptionId($val){
	global $conn;
	
	$id=self::getSmokingOptionId($val);
	if(!$id){
		$sql="INSERT into smoking(id,smoking_status,created_on) VALUES(DEFAULT,:val,NOW())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('val',$val);
		try{$sth->execute();
		$id=$conn->lastInsertId();
		}
		catch(Exception $e){}
		
	}
	return $id;
	}
	
}
?>
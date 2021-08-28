<?php
	if(isset($_POST)){
	$email=$_POST['email'];
	$pass=$_POST['password'];
	$shapass=sha1($pass);

	// check if the user exist in the database
	$stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE email = ? AND password=?");
	$stmt->execute(array( $email,$shapass));
	$row= $stmt->fetch();
	$count= $stmt->rowCount();
	if($count>0){
		//update the database to make user online
		$stmt1 = $con->prepare("UPDATE  socialnetwork. users SET online='1' WHERE userid=?");
	    $stmt1->execute(array($row['userid']));


		  
		  $_SESSION['username']=$row['name'];   //register session name 
          $_SESSION['id']=$row['userid'];    //register session id
          echo 'exist';
          
	}else{
		echo 'not_exist';
	}
}
?>
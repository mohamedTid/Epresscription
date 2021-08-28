<?php

	session_start(); // start the session
	include 'conct.php';
	//update the database to make user offline
		$stmt1 = $con->prepare("UPDATE  socialnetwork. users SET online='0' WHERE userid=?");
	    $stmt1->execute(array($_SESSION['id']));

	session_unset(); // unset the data

	session_destroy(); // destory the session

	header('location: login.php');
	exit();
	?>
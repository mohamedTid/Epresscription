<?php
session_start();
$nosidebar=$nonavbar=''; //variable to not include the navbar or sidbar  if is exist and

if(isset($_GET['do'])){
	$do =$_GET['do'];
}else{
	$do ='manage';
}
include 'conct.php';

if($do=='addpost'){

if($_SERVER['REQUEST_METHOD']=="POST"){
	     $description=$_POST['description'];
         $file=''; // the image name 
		 $folder_dir= 'layout/image/posts/'; // posts images directory 
		 $base =basename($_FILES['image']['name']); //function  return the final name component of path(image:come from input field )
         $imageFileType=pathinfo($base, PATHINFO_EXTENSION); // return (jpg or png )
         // check if file exist 
         if(file_exists($_FILES['image']['tmp_name'])){ 
         	if( $imageFileType=='jpg' ||  $imageFileType=='png'){ //check from the format of the image 
         		      $file=uniqid(). '.' .$imageFileType ; //return unique string with extantion (exp: 154snkqksqsn78ml.jpg)
                       $fileName= $folder_dir . $file; // the path of the img exp: layout/image/profile_img/ 154snkqksqsn78ml.jpg
         		       move_uploaded_file($_FILES['image']['tmp_name'], $fileName); // remove the image from temporery location to file directory

         	}else{
         		$_SESSION['uploadError'] = "wrong Format. only jpg or png allowed.";
			      
         	}

         }

	  $stmt=$con->prepare('INSERT INTO socialnetwork. post( userid, description, image) VALUES (:userid,:description ,:image)');
	  $stmt->execute(array(
	 	'userid' => $_SESSION['id'],
	 	'description'=>$description,
	 	'image'=> $file
	      ));
	      $count= $stmt->rowCount();
	      if($count>0){
	      	$url=$_SERVER['HTTP_REFERER'];
	      		header("Location:".$url."");
		        exit();
	      }else{
	      	echo $stmt->errorInfo();
	      }
}
}elseif($do =='addlike'){
	if(isset($_POST)){
        $idpost=$_POST['idpost'];
		$stmt=$con->prepare('INSERT INTO socialnetwork. likes( userid, postid, like_ok) VALUES (:userid,:postid , 1)');
		$stmt->execute(array(
	 	'userid' => $_SESSION['id'],
	 	'postid'=> $idpost 
	      ));
		$count= $stmt->rowCount();
		if($count>0){
			echo 'ok';
		}else{
			echo 'errpr';
		}
	}

}elseif ($do=='adduser'){
	if($_SERVER['REQUEST_METHOD']=="POST"){
     $name=$_POST['name'];
	 $email=$_POST['email'];
	 $pass1=$_POST['password'];
	 $hashpass=sha1($pass1);
	 $stmt=$con->prepare('INSERT INTO socialnetwork. users(name,email,password) VALUES (:user, :email, :pass)');
	 $stmt->execute(array(
	 	'user' => $name,
	 	'email'=>$email,
        'pass' =>$hashpass
	      ));
	  $count= $stmt->rowCount();
	  if($count>0){
	  	$_SESSION['registered']=true;
	  	echo 'registered';
	  }else{
	  	echo 'not_registered';
	  }
 
   }
}elseif($do=='checkemail'){
	
if(isset($_POST)){
     $email=$_POST['email'];
      $stmt = $con->prepare("SELECT email FROM socialnetwork. users  WHERE email = ? ");
	  $stmt->execute(array( $email));
	  $count= $stmt->rowCount();
	  if($count>0){
	    echo 'exist';
	    }else{
	    echo 'not_exist';
	      }

}

}elseif ($do=='checkuser') {
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
}elseif ($do =='addcomment') {
	    if(isset($_POST)){

        $idpost= $_POST['idpost'];
        $comment=$_POST['comment1'];
		$stmt=$con->prepare('INSERT INTO socialnetwork.  comments( userid, postid, comment) VALUES (:userid,:postid, :comment)');
		$stmt->execute(array(
	 	'userid'  => $_SESSION['id'],
	 	'postid'  => $idpost,
	 	'comment' => $comment
	      ));
		$count= $stmt->rowCount();
		if($count>0){
			echo 'ok';
		}else{
			echo 'error';
		}
	}

}elseif ($do=='search') {

    	if(isset($_POST['txt'])){
    	 $name=$_POST['txt'];
    	 $result='';
      	 $stmt=$con->prepare("SELECT * FROM socialnetwork. users WHERE name LIKE '%".$name."%' ");
      	 $stmt->execute();
      	 $count=$stmt->rowCount();
      	 $rows=$stmt->fetchAll();
      	if($count > 0){
      		$result.='<p>search result</p>';

      		foreach ($rows as $row) {
      			$result.='<div class="iteam-search">';
      			     $picture=$row['profileimage'];
      			      if($picture !=''){
		 				$path="layout/image/profile_img/".$row["profileimage"];
		 				 } else{ 
		 				$path="layout/image/avatar.png";
		 				 } 
      			$result.='<a href="view-profile.php?id='.$row['userid'].'">
      		    <img class="img-circle sm-img" src='.$path.' ></img><span>'.$row['name'].'</span></a>';

      			$result.='</div>';

      		}
              echo $result;
      	}else{
      		echo '<p>no result !</p>';
      	}
     }


}elseif($do=='send-request'){
	if(isset($_GET)){
		 $stmt=$con->prepare("SELECT * FROM socialnetwork.  friendsrequest WHERE userid= ? AND friendid = ?");
      	 $stmt->execute(array( $_SESSION['id'], $_GET['id']));
      	 $count=$stmt->rowCount();
      	 if($count == 0){
      	 	$stmt1=$con->prepare("INSERT INTO  socialnetwork.  friendsrequest(userid, friendid ) VALUES (:user, :friend) ");
      	   $stmt1->execute(array(
      	   	   'user'=>$_SESSION['id'],
      	   	   'friend'=>$_GET['id']
      	   ));
      	   $count1=$stmt1->rowCount();
      	   if( $count1 > 0){
      	   	$url= 'view-profile.php?id='. $_GET['id']; 
      	   	header("Location:".$url." ");
      	   	exit();
      	   }else{
      	   	echo $con->error;

      	   }
      	 }else{
      	 	$url= 'view-profile.php?id='. $_GET['id']; 
      	   	header("Location:". $url ." ");
      	   	exit();
      	 }

	}
}elseif ($do=='remove-request') {
		if(isset($_GET)){
		 $stmt=$con->prepare("SELECT * FROM socialnetwork.  friendsrequest WHERE userid= ? AND friendid = ?");
      	 $stmt->execute(array( $_SESSION['id'], $_GET['id']));
      	 $count=$stmt->rowCount();
      	 if($count > 0){
      	 	$stmt1=$con->prepare("DELETE FROM  socialnetwork.  friendsrequest WHERE  userid= ? AND friendid = ? ");
      	   $stmt1->execute(array($_SESSION['id'], $_GET['id']));

      	   $count1=$stmt1->rowCount();
      	   if( $count1 > 0){
      	   	$url= 'view-profile.php?id='. $_GET['id']; 
      	   	header("Location:".$url." ");
      	   	exit();
      	   }else{
      	   	echo $con->error;

      	   }
      	 }else{
      	 	$url= 'view-profile.php?id='. $_GET['id']; 
      	   	header("Location:". $url ." ");
      	   	exit();
      	 }

	}
	
}elseif ($do =='accept-request') {

	 if(isset($_GET)){
	 	//check  if there is request 
			  $stmt=$con->prepare("SELECT * FROM socialnetwork.  friendsrequest WHERE userid= ? AND friendid = ?");
	      	  $stmt->execute(array( $_GET['id'], $_SESSION['id']));
	      	  $count=$stmt->rowCount();
	      	if($count > 0){
                  //delete the requeste 
	              $stmt1=$con->prepare("DELETE FROM socialnetwork.  friendsrequest WHERE userid= ? AND friendid = ? ");
	              $stmt1->execute(array( $_GET['id'], $_SESSION['id']));
	              $count1=$stmt1->rowCount();
	              if($count1 >0){
	              	//insert (accept ) the request into friends table fromm user to friend
		      	 	      $stmt2=$con->prepare("INSERT INTO  socialnetwork.  friends(userid, id_friend_user,friend_ok ) VALUES (:user, :friend, :ok) ");
		      	          $stmt2->execute(array(
		      	   	       'user'=>$_SESSION['id'],
		      	   	       'friend'=>$_GET['id'],
		      	   	       'ok'=> 1));
		      	         $count2=$stmt2->rowCount();
		      	        if($count2 > 0){
		      	        	//insert (accept ) the request into friends table fromm friend to user
		      	        	$stmt3=$con->prepare("INSERT INTO  socialnetwork.  friends(userid, id_friend_user,friend_ok ) VALUES (:user, :friend, :ok) ");
                            $stmt3->execute(array(
		      	   	       'user'=>$_GET['id'],
		      	   	       'friend'=>$_SESSION['id'],
		      	   	       'ok'=> 1));
                            $count3=$stmt3->rowCount();
                            if($count3>0){
		      	   	           
		      	   	          header("Location:friends-request.php");
		      	   	          exit();
		      	   	         }else{
		      	   	      	   echo $con->error;
		      	   	         }
		      	         }else{
		      	   	     echo $con->error;

		      	       }
	      	     }
	      	    }else{
		      	 	  header("Location:friends-request.php");
		      	   	 exit();

		    }
   }
}elseif ($do=='remove-friend') {

if(isset($_GET)){
	 	//check  if there is request 
			  $stmt=$con->prepare("SELECT * FROM socialnetwork.  friends WHERE userid= ? AND id_friend_user = ?");
	      	  $stmt->execute(array( $_GET['id'], $_SESSION['id']));
	      	  $count=$stmt->rowCount();
	      	if($count > 0){
                  //delete the requeste 
	              $stmt1=$con->prepare("DELETE FROM socialnetwork.  friends WHERE userid= ? AND id_friend_user = ? ");
	              $stmt1->execute(array( $_GET['id'], $_SESSION['id']));
	              $count1=$stmt1->rowCount();
	              if($count1 >0){
	              $stmt2=$con->prepare("DELETE FROM socialnetwork.  friends WHERE userid= ? AND id_friend_user = ? ");
	              $stmt2->execute(array( $_SESSION['id'], $_GET['id']));
	              $count2=$stmt2->rowCount();
	              if($count2 > 0){
	              	 $url=$_SERVER['HTTP_REFERER'];
	      		     header("Location:".$url."");
		              exit();
	              }

	      	     }
	      	    }else{
                        $url=$_SERVER['HTTP_REFERER'];
	      		        header("Location:".$url."");
		                exit();

		    }
   }

}elseif ($do == 'add-friend-post') {

if($_SERVER['REQUEST_METHOD']=="POST"){
	     $description=$_POST['description'];
	     $friendid=$_POST['id'];
         $file=''; // the image name 
		 $folder_dir= 'layout/image/posts/'; // posts images directory 
		 $base =basename($_FILES['image']['name']); //function  return the final name component of path(image:come from input field )
         $imageFileType=pathinfo($base, PATHINFO_EXTENSION); // return (jpg or png )
         // check if file exist 
         if(file_exists($_FILES['image']['tmp_name'])){ 
         	if( $imageFileType=='jpg' ||  $imageFileType=='png'){ //check from the format of the image 
         		      $file=uniqid(). '.' .$imageFileType ; //return unique string with extantion (exp: 154snkqksqsn78ml.jpg)
                       $fileName= $folder_dir . $file; // the path of the img exp: layout/image/profile_img/ 154snkqksqsn78ml.jpg
         		       move_uploaded_file($_FILES['image']['tmp_name'], $fileName); // remove the image from temporery location to file directory

         	}else{
         		$_SESSION['uploadError'] = "wrong Format. only jpg or png allowed.";
			      
         	}

         }

	  $stmt=$con->prepare('INSERT INTO socialnetwork. friends_post(userid, friendid, description, image) VALUES (:userid, :friendid, :description ,:image)');
	  $stmt->execute(array(
	 	'userid' => $_SESSION['id'],
	 	'friendid' => $friendid,
	 	'description'=>$description,
	 	 'image'=> $file
	      ));
	      $count= $stmt->rowCount();
	      if($count>0){
	      	$url=$_SERVER['HTTP_REFERER'];
	      		header("Location:".$url."");
		        exit();
	      }else{
	      	echo $stmt->errorInfo();
	      }
}
	
}
?>
<?php
session_start();
if(isset($_SESSION['id'])){
	$title='Tidda.com';
       include 'init.php';
     if(isset($_GET['do'])){
	      $do =$_GET['do'];
     }else{
	    $do ='manage';
      }
?>
<div class="dash bg-gris">
	<div class="dash1 ">
		 <div class=" dash2  margin-pull-r">
		 	<section class="fd-head" ><h1 >Friends</h1></section>
		 	<?php //select all the users exepet my self
		 	     $stmt = $con->prepare("SELECT * FROM socialnetwork.friendsrequest WHERE friendid= ?  ");
	             $stmt->execute(array( $_SESSION['id']));
	             $cs= $stmt->fetchAll();
	             $result=$stmt->rowCount();
	             if($result>0){

	             	foreach ($cs as $c) {
	               $stmt1 = $con->prepare("SELECT * FROM socialnetwork.users WHERE userid= ? LIMIT 1");
	               $stmt1->execute(array( $c['userid']));
	              $rows= $stmt1->fetchAll();
	              $result=$stmt1->rowCount();
	              foreach ($rows as $row ) {
	              
		 	?>
		 	<section class="contant" style="padding-right: 25px;" >
		 		<div class=" box friends-box">
		 				<div class="user-image" >
		 			     <?php  if($row['profileimage'] !=''){  ?>
		 				<img class="img-circle" src="layout/image/profile_img/<?php echo $row['profileimage'] ;?>">
		 				<?php } else{ ?>
		 				<img  class="img-circle" src="layout/image/avatar.png">
		 				<?php  } ?>
		 				</div>	
		 				<div class="friend-info">
		 				<h3><?php echo $row['name'] ;?></h3>
		 				<h5><?php echo $row['designation']; ?></h5>
		 				</div>
		 				<div class="box-bnts" style="top:35px">
		 					<?php 
		 					//$stmt1 = $con->prepare("SELECT * FROM socialnetwork. friends  WHERE userid = ? AND id_friend_user=? "); 
				             //$stmt1->execute(array( $_SESSION['id']), $row['userid'] );
				             //$result1=$stmt1->rowCount();
	             ?>
		 					<a href="manage.php?do=accept-request&id=<?php echo  $row['userid'] ?>" class="btn-a bg-maroon" style="padding: 6px 21px;">Accept</a>
		 					
		 				</div>
		 		</div>

		 	</section>

		 	 <?php	}
		 	}
	             } ?>
		 </div>
	</div>
</div>
<?php }elseif($do=='send-msg'){?>

	<div class="dash bg-gris">
	<div class="dash1 ">
		 <div class=" dash2  margin-pull-r">
		 	<section class="fd-head" ><h1 >Friends</h1></section>
	              message page <br>
	              <?php echo $_GET['id']; ?>
	        </section>
	     </div>
	</div>
</div>

<?php include $tpl.'footer.php';
       include $tpl.'footer_end.php';
}else{
	header('location:login.php');
	exit();
}

?>
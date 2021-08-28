<?php
session_start();
if(isset($_SESSION['id'])){
	$title='Tidda.com';
       include 'init.php';
?>
<div class="dash bg-gris">
	<div class="dash1 ">
		 <div class=" dash2  margin-pull-r">
		 	<section class="fd-head" ><h1 >Friends</h1></section>
		 	<?php //select all the users excepet my self
		 	     $stmt = $con->prepare("SELECT * FROM socialnetwork. friends WHERE userid= ?  AND friend_ok = ? ");
	             $stmt->execute(array( $_SESSION['id'], 1));
	             $cs= $stmt->fetchAll();
	             $result=$stmt->rowCount();
	             if($result>0){

	             	foreach ($cs as $c) {
	               $stmt = $con->prepare("SELECT * FROM socialnetwork. users WHERE userid= ?  ");
	               $stmt->execute(array( $c['id_friend_user']));
	              $rows= $stmt->fetchAll();
	              $result=$stmt->rowCount();
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
		 				<div class="box-bnts">
		 					<a href="view-profile.php?id=<?php echo $row['userid']; ?>" class="btn-a bg-maroon" >View profile</a>
		 					<a href="manage.php?do=remove-friend&id=<?php echo $row['userid']; ?>" class="btn-a bg-red">Remove</a>
		 					<a href="?do=send-msg&id=<?php echo $row['userid'];?>" class="btn-a bg-green">Send message</a>
		 				</div>
		 		</div>

		 	</section>

		 	 <?php	}
		 	}
	             } ?>





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
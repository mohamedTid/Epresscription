<?php
session_start();
if(isset($_SESSION['id'])){

	$id=$_GET['id'];
	$title='profile friend';
       include 'init.php';
	if(empty($_GET['id'])){
		header('Location:friends.php');
		exit();

	}elseif($_GET['id'] == $_SESSION['id']){
         header('Location:login.php');
		exit();
	}else{
?>
<div class="dash bg-gris">
	<div class="dash1 ">
		 <div class=" dash2 margin-pull-r">
		 	<?php 
		 	 $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	                                   $stmt->execute(array( $id));
	                                   $r= $stmt->fetch();
	                                   $c=$stmt->rowCount();
	                                  $name= $r['name'];
	        ?>
		 	<section class="fd-head" style="position: relative;"><h1 ><?php echo  $name; ?> profile</h1>
		 		<?php
					//to check if there ih friendship or no
		 	    $stmt2 = $con->prepare("SELECT * FROM socialnetwork. friends  WHERE userid = ? AND id_friend_user =? OR userid= ? AND   id_friend_user = ? ");
	            $stmt2->execute(array($_SESSION['id'], $id,$id,$_SESSION['id']));
	            $count2=$stmt2->rowCount();

		 		 ?>
		 		<div class="pull-right" style="    position: absolute; top: 18px; right: 45px;">
		 			<?php  //to check if ther is friendship or no 
		 			if($count2 >0){?>
		 		        <a class="btn-a bg-red" href="manage.php?do=remove-friend&id=<?php echo $id;?>">Remove friend</a>
		 		    <?php }else{
		 		    	//check if there is request friend 
					 		 $stmt3 = $con->prepare("SELECT * FROM socialnetwork. friendsrequest  WHERE userid = ? AND friendid =? ");
	            		     $stmt3->execute(array($_SESSION['id'],$id));
	            		     $count3=$stmt3->rowCount();
	            		     if($count3 == 0){?>
                                     
	            		     	<a class="btn-a bg-green" href="manage.php?do=send-request&id=<?php echo $r['userid'] ;?>" style="border-color: #43926e;">Add friend</a>
	            		   <?php  }else{?>
	            		   	<a class="btn-a bg-yallow" href="manage.php?do=remove-request&id=<?php echo $r['userid'] ;?>" style="border-color: #e08e0b;" >Remove request</a>
	            		  <?php } ?>	
		 			 
		 			<?php } ?>
		 		</div>
		 	</section>
             <section class="contant" style="padding-right: 25px;" >
				<!-- =============== START USER INFORMATION FIELD =========================================== -->
				<div class="col3">
					<div class="box border-blue">
						<div class="box-body">
							 <?php 
							           
							           $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	                                   $stmt->execute(array( $id));
	                                   $r= $stmt->fetch();
	                                   $c=$stmt->rowCount();
	                                   if($c>0){
	                                 	   $picture=$r['profileimage'];
	                                 	if($picture!=''){
	                                 		$dir_img= $img.'profile_img/'. $picture ;?>
	                                 		<img class="profile-img img-circle" src="<?php echo $dir_img ?>" >
	                                 <?php	}else{ ?>
	                                 	<img class=" profile-img img-circle" src="layout/image/avatar.png" >

	                                <?php  }
	                                 }
							 ?>
							
							<h3 class="profile-user-name text-center"><?php echo   $name ;?></h3>
							<p class="text-center profile-p"><?php echo $r['designation'];?></p>

							<ul class="group-list">
								<li class="list-item"> <b>Followers</b> <a class="pull-right a-color">5</a></li>
								<li class="list-item"><b>Following</b> <a class="pull-right  a-color">7</a></li>
								<li class="list-item"><b>Friends</b> <a class="pull-right a-color "><?php echo 7 ?></a></li>
							</ul>
						</div>
					</div>
					<div class="box border-blue">
						<div class="box-hd">
							<h3 class="box-title">About me </h3>
						</div>
						<div class="box-body">
							<div class="about-me">
							 <strong><i class="fa fa-book "></i> Education</strong>
							 <?php  if( $r['degree']!='' && $r['country']!=''){?>
							 <p class=" profile-p"> <?php echo $r['degree']; ?> from  <?php echo $r['university']; ?></p>
							 <?php } ?>
							 <hr>
							 <strong><i class="fa fa-map-marker  "></i>  Location</strong>
							  <?php if ( $r['city']!='' &&  $r['country']!=''){?>
							 <p class=" profile-p"> <?php echo $r['city'] ; ?>, <?php echo $r['country'];  ?></p>
							 <?php }?>

							 <hr>
							 <strong><i class="fa fa-pencil "></i> Skills</strong>
							 <p>
							 	<?php
							 	    //explode() function : to split or separete a string by another string 
							 	$arr=explode(",", $r['skills']); //separete the skills each one by one 
							 	//array to change background color of spans(desgin )
							 	$colors=array("bg-yallow" , "bg-black","bg-blue","bg-green", "bg-red");
							 	foreach($arr as $ar){
							 		$color=array_rand($colors);//each time choose a color 
							 		$val=$colors[$color]; //pass the values from the array
							 		?>

							 	<span class=" label <?php echo $val ?>" ><?php echo $ar ; ?></span>
							 	<?php }?>

							 </p>
							 <hr>
							 <strong><i class="fa fa-file-text-o "></i> About Me</strong>

                                  <p class="label profile-p" "><?php echo $r['aboutme'] ; ?></p>
							</div>
						</div>
						
					</div>
					
				</div> 
			<!-- =============== END USER INFORMATION FIELD =========================================== -->
			<!-- ===================================== START HOMEPAGE ========================================================== -->
				<div class="col4  menu-1 " id="page1">
					<?php //to check if ther is friendship or no 
	            if($count2>0){

		 	  ?>
					<div class="box border-blue">
		 					<div class="box-hd"> <h3 class="box-title">Wall</h3> </div>
		 					<form class="form-h" action="manage.php?do=add-friend-post" method="POST"  enctype="multipart/form-data">
		 						<div class="box-body">
		 							<div class="form-group">
		 								<div class="form-sd">
		 									<textarea  name="description" placeholder="What's on your mind?"></textarea>
		 									<input type="hidden" name="id" value="<?php echo $id; ?>">
		 								</div>
		 							</div>
		 						</div>
		 						<div class="box-footer">
		 							<button type="submit" class="btn-post">Post</button>
		 							<div class="pull-right " style=" margin-right: 5px;">
		 								<label class="btn-img">image
		 									<input type="file" name="image" class="visible">
		 								</label>
		 							</div>
		 							<div class="pull-right " style=" margin-right: 5px;">
		 								<label class="btn-img">video
		 									<input type="file" name="post-video" class="visible" accept=".mp4">
		 								</label>
		 							</div>
		 							<div class="pos-errors"><?php
		 							if(isset($_SESSION['uploadError'])){ ?>
		 							 <p class="color-red "><?php  echo $_SESSION['uploadError'];?></p>

		 							<?php unset($_SESSION['uploadError']);} ?> </div>
		 						</div>
		 					</form>
		 				</div>
		 				<?php } ?>

		 	<!-- =============================== START POSTS FIELD ================================================================== -->
		 				<div class="posts" >
		 					<?php 
		 				//to select all posts from post table and information about the friend
		 				//and all infromation from  friends_post to display post frind and the other friends post's 	
		 				$sql1=$con->prepare("SELECT * FROM (SELECT post.postid, post.userid, post.description,post.image,post.create_date,users.name,users.profileimage FROM socialnetwork .post INNER JOIN socialnetwork. users ON post.userid=users.userid WHERE post.userid='$id' UNION SELECT friends_post.postid, friends_post.userid,friends_post.description, friends_post.image, friends_post.create_date, users.name,users.profileimage FROM socialnetwork. friends_post INNER JOIN socialnetwork. users ON friends_post.userid=users.userid WHERE friends_post.friendid='$id') post ORDER BY post.create_date DESC ");

		 					//to select all posts from post table and information about the friend 
		 					  //	$sql1= $con->prepare("SELECT * FROM socialnetwork. post INNER JOIN socialnetwork. users WHERE post.userid= users.userid AND users.userid=? ORDER BY post.postid DESC ");
							        $sql1->execute(array());
							          $posts=$sql1->fetchAll();
							          $count=$sql1->rowCount();
							          if($count>0){
							          	foreach($posts as $post){ ?>

                               <div class="box">
		 					  <div class="box-hd">
		 						<div class="user-block">
		 							<?php if($picture!=''){
	                                 		$dir_img= $img.'profile_img/'. $picture ;?>
	                                 		<img class=" post-img profile-img img-circle" src="<?php echo $dir_img ?>" >
	                                 <?php	}else{ ?>
	                                 	<img class="post-img profile-img img-circle" src="layout/image/avatar.png" >

	                                <?php  } ?>
		 							<span class="username"> <a class="a-color" href="view-profile.php?id=<?php echo $id ; ?>"><?php echo  $name;?></a></span>
		 							<span class="share-date"> Shared publicly - <?php echo date('d-M-Y h:i a', strtotime($post['create_date'])); ?></ ?></span>
		 							
		 						 </div>
		 					  </div>

		 					<div class="box-body post-field">
		 						<p style="margin-left: 10px;"><?php  echo $post['description'];?></p>
		 						<?php 
		 						if($post['image']!=""){ ?>
                                       <img class="img-shared" src="<?php echo 'layout/image/posts/'.$post['image']; ?>" >
		 						<?php } ?>
		 					</div>
							

		 					<div class="comment-like-text">

		 					 <ul class="like-comment">
		 					 	<?php //to check if the is like in this post or no 
		 					 	      $sql1= $con->prepare("SELECT * FROM socialnetwork. likes WHERE userid=? AND postid=?  ");
							          $sql1->execute(array($_SESSION['id'], $post['postid']));
							          $r= $sql1->fetch();
							          $count1=$sql1->rowCount();
							          $like_ok=$r['like_ok'];

		 					 	?>
<?php //to check if ther is friendship or no 
if($count2>0){  ?>
		 					  <li>
		 					  	<?php if($like_ok==0 ){?>

		 					  	<button data-id="<?php echo $post['postid'] ;?>" class="addlike btn-like-comment"><i class="fa fa-thumbs-o-up"></i>Like</button>
		 					  <?php	}else{ ?>
		 					       <button  class=" addlike btn-like-comment " disabled style="cursor:not-allowed" ><i class="fa fa-thumbs-o-up"></i> like</button>
		 					  <?php }?>

		 					  	
		 					  </li>


		 					  <li><button class="btn-like-comment"><i class="fa fa-share"></i> share</button></li>
<?php }?>
		 					     <?php 
		 					        //to calculate the all likes in the post and desplay it
		 					 	      $sql2= $con->prepare("SELECT * FROM socialnetwork. likes WHERE  postid=?  ");
							          $sql2->execute(array($post['postid']));
							          $likes=$sql2->rowCount();
							          $totalLikes=(int)$likes; 

		 					        //to calculate the all comments in the post and desplay it
		 					 	     $sql3= $con->prepare("SELECT * FROM socialnetwork. comments WHERE  postid=?  ");
							          $sql3->execute(array($post['postid']));
							          $comment=$sql3->rowCount();
							          $totalComments=(int)$comment;	 					 	
							      ?>

		 					  <span class="pull-right comm-like"><?php echo $totalComments; ?> comments </span>
		 					  <span class="pull-right comm-like">-</span>
							   
		 					  <span class="pull-right comm-like"><?php echo $totalLikes; ?> likes</span>
		 					</ul>

                                     <?php //to check if the is comment in this post or no 
		 					 	      $sql4= $con->prepare("SELECT * FROM socialnetwork. comments WHERE userid=? AND postid=? ORDER BY comment_id  DESC  LIMIT 4 ");
							             $sql4->execute(array($_SESSION['id'], $post['postid']));
							               $comments= $sql4->fetchAll();
							              $count4=$sql4->rowCount();
							          ?>

									    <?php if($count4>0){ ?>
									    <?php	//to check if ther is friendship or no 
									      if($count2>0) {?>
									 	<div class="comments-text">
									      <?php foreach ($comments as $comment ) {
									      $sql5= $con->prepare("SELECT * FROM socialnetwork. users WHERE userid=? ");
							                $sql5->execute(array($comment['userid']));
							                $users= $sql5->fetch();
							                 $count5=$sql5->rowCount();
							           if($users['profileimage'] != ''){?>
							           	   <img class=" post-img profile-img img-circle" src="<?php echo 'layout/image/profile_img/'.$users['profileimage'] ;?>" >
							           	<?php }  else{ ?>
							           	<img class=" post-img profile-img img-circle" src="layout/image/avatar.png">
							           <?php	}	?>
		 					  	              <div class="comment-added" >
		 					  	          	          <span class="username"> <?php echo $users['name']; ?>
		 					  	          		       <span class="pull-right"><?php echo date('d-M-Y a', strtotime($comment['comment_date']));?></span>
		 					  	          	          </span>
		 					  	         
		 					  	           <?php echo $comment['comment'] ?>
		 					  	            </div>

		 					 			 
									<?php } ?>
									 </div>
									 <?php } ?>
								<?php } ?>
		 					  <br>
                            <?php //to check if ther is friendship or no 
                            if($count2>0){?>
                             <div class="com-field" >
                             	        <?php if($picture!=''){
	                                 		$dir_img= $img.'profile_img/'. $picture ;
	                                        ?>
	                                 		<img class=" post-img profile-img img-circle" src="<?php echo $dir_img ?>" >
	                                     <?php	}else{ ?>
	                                 	<img class="post-img profile-img img-circle" src="layout/image/avatar.png" >

	                                    <?php  } ?>   
	                                                           
                                      <input data-id="<?php echo $post['postid'] ;?>" class="input-comment" type="text" placeholder="Type a comment"  onkeypress="checkInput(event);">
                  
                                 </div>
                                 <?php }?>
                          </div>


		 				</div>
							          <?php	} 
							          	
							          }?>	
						</div> 
		<!-- =============================== END POST FIELD ================================================================== -->
						</div> 
		<!-- ===================================== END HOMEPAGE ========================================================== -->




	        </section>
	     </div>
	</div>
</div>
<?php include $tpl.'footer.php';
       include $tpl.'footer_end.php';
       }
}else{
	header('location:login.php');
	exit();
}

?>
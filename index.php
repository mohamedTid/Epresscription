<?php
session_start();
if(isset($_SESSION['id'])){
	$title='Tidda.com';
       include 'init.php';
?>
	<?php 
	//get information from database to  display it in fields and update  it 
	   $name ='';
	   //select all fields in database
        $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	    $stmt->execute(array( $_SESSION['id']));
	    $filed= $stmt->fetch();
         $count_row= $stmt->rowCount();
      //check if inforamation exist and display it in form fields
	  if($count_row>0){
	 	 $name =$filed['name'];
	 	  $picture=$filed['profileimage'];
	 	 } ?>

<div class="dash bg-gris">
	<div class="dash1 ">
		 <div class=" dash2  margin-pull-r">
		 	<section class="fd-head" ><h1 >Home page</h1></section> 
		 	<section class="contant">
		 			<div class="col1">
                         <div class="box border-blue">
		 					<div class="box-hd"> <h3 class="box-title">Wall</h3> </div>
		 					<form class="form-h" action="manage.php?do=addpost" method="POST"  enctype="multipart/form-data">
		 						<div class="box-body">
		 							<div class="form-group">
		 								<div class="form-sd">
		 									<textarea  name="description" placeholder="What's on your mind?"></textarea>
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
		 	<!-- =============================== START POSTS FIELD ================================================================== -->
		 				<div class="posts" >
		 					<?php
		 					  	$sql= $con->prepare("SELECT * FROM socialnetwork. post INNER JOIN socialnetwork. users WHERE post.userid= users.userid AND users.userid=? ORDER BY post.postid DESC ");
							        $sql->execute(array($_SESSION['id']));
							          $posts=$sql->fetchAll();
							          $count=$sql->rowCount();
							          if($count>0){
							          	$i=0;
							          	foreach($posts as $post){ 
							          		$i++;
							          		?>


                               <div class="box">
		 					  <div class="box-hd">
		 						<div class="user-block">
		 							<?php if($picture!=''){
	                                 		$dir_img= $img.'profile_img/'. $picture ;?>
	                                 		<img class=" post-img profile-img img-circle" src="<?php echo $dir_img ?>" >
	                                 <?php	}else{ ?>
	                                 	<img class="post-img profile-img img-circle" src="layout/image/avatar.png" >

	                                <?php  } ?>
		 							<span class="username"> <a class="a-color" href="profile.php"><?php echo $name;?></a></span>
		 							<span class="share-date">Shared publicly - <?php echo date('d-M-Y h:i a', strtotime($post['create_date'])); ?></ ?></span>
		 							
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
		 					  <li>
		 					  	<?php if($like_ok==0 ){?>

		 					  	<button data-id="<?php echo $post['postid'] ;?>" class="addlike btn-like-comment"><i class="fa fa-thumbs-o-up"></i>Like</button>

		 					  <?php	}else{ ?>
		 					       <button  class=" addlike btn-like-comment " disabled style="cursor:not-allowed" ><i class="fa fa-thumbs-o-up"></i> like</button>
		 					  <?php }?>

		 					  	
		 					  </li>


		 					  <li><button class="btn-like-comment"><i class="fa fa-share"></i> share</button></li>

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

		 					  <span class="pull-right comm-like" onclick="toggleComments(<?php echo $i; ?>);" style="cursor: pointer;"><?php echo $totalComments; ?> comments </span>
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
									 	<div class="comments-text" id="boxComment<?php echo $i;?>" style="display: none;" >
									      <?php foreach ($comments as $comment ) {
									      $sql5= $con->prepare("SELECT * FROM socialnetwork. users WHERE userid=? ");
							                $sql5->execute(array($comment['userid']));
							                $users= $sql5->fetch();
							                 $count5=$sql5->rowCount();
							           if($users['profileimage'] != ''){?>
							           	   <img class=" post-img profile-img img-circle" src="<?php echo 'layout/image/profile_img/'.$users['profileimage'] ;?>" >
							           	<?php }  else{ ?>
							           	<img class="post-img profile-img img-circle" src="layout/image/avatar.png">
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
		 					  <br>

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
                             
                            
                          </div>

		 				</div>
							          <?php	} 
							          	
							          }?>	
						</div> 
		<!-- =============================== END POST FIELD ================================================================== -->
		 			</div>

		 			<div class="col2">
		 <!-- ========================================Online friends ============== ========================================= -->		
		 					<?php 
		 							$stmt3=$con->prepare("SELECT * FROM socialnetwork. friends INNER JOIN socialnetwork. users ON 
		 							 friends.id_friend_user=users.userid WHERE  friends.userid= ? AND users.online=?");
						             $stmt3->execute(array($_SESSION['id'],1));
						             $rows1= $stmt3->fetchAll();
						             $result3=$stmt3->rowCount();
						          if($result3>0){
                            ?>
		 				<div class="box border-red">

		 					<div class="box-hd">
		 						<h3 class="box-title">Online freinds</h3>
		 						<span class="pull-right bg-green friends-result"><?php echo $result3;?> friends</span>
		 					</div>
		 					<div class="box-body">
		 						<ul  class="freind-list">
		 						<?php 
						             	foreach ($rows1 as $row1) {
		 						?>
		 							<li>
		 								<?php
		 								if($row1['profileimage'] !=' '){?>
		 								<img src="layout/image/profile_img/<?php echo $row1['profileimage'] ;?>" >
		 							<?php }else{?>
		 								  <img src="layout/image/image.jpg">
		 								<?php } ?>
		 								<a class="users-list-name" href="view-profile.php?id=<?php echo $row1['userid'] ;?>"><?php echo $row1['name'];?></a>
		 							</li>
		 							<?php } ?> 							
		 						</ul>
		 					</div>
				
		 					<div class="box-footer text-center">
		 						<a class="view-all" href="friends.php" >VIEW ALL FREINDS</a>
		 					</div>
		 				</div>
		 			<?php }	 ?>
	<!-- ========================================all friends ============== ========================================= -->
		 				<div class="box border-red">
		 					<?php 
		 							$stmt1=$con->prepare("SELECT * FROM socialnetwork. friends INNER JOIN socialnetwork. users ON 
		 							 friends.id_friend_user=users.userid WHERE  friends.userid= ?");
						             $stmt1->execute(array($_SESSION['id']));
						             $rows= $stmt1->fetchAll();
						             $result=$stmt1->rowCount();
                            ?>
		 					<div class="box-hd">
		 						<h3 class="box-title">All freinds</h3>
		 						<span class="pull-right bg-green friends-result"><?php echo $result; ?> freinds</span>
		 					</div>
		 					<div class="box-body">
		 						<ul  class="freind-list">
		 						<?php 
						             if($result>0){
						             	foreach ($rows as $row) {
		 						?>
		 							<li>
		 								<?php
		 								if($row['profileimage'] !=' '){?>
		 								<img src="layout/image/profile_img/<?php echo $row['profileimage'] ;?>" >
		 							<?php }else{?>
		 								  <img src="layout/image/image.jpg">
		 								<?php } ?>
		 								<a class="users-list-name" href="view-profile.php?id=<?php echo $row['userid'] ;?>"><?php echo $row['name'];?></a>
		 							</li>
		 							<?php }
						             }		 ?> 							
		 						</ul>

		 					</div>
		 					
		 					<div class="box-footer text-center">
		 						<a class="view-all" href="friends.php" >VIEW ALL FREINDS</a>
		 						
		 					</div>
		 				</div>

                          <div class="box border-blue">
                          	<div class="box-hd">
                          		<h3 class="box-title">Suggested Pages</h3>
                          	</div>
                          	<div class="box-body">
                          		<ul class="page-list page-box ">
                          			<li class="item">
                          				<div class="page-img">
                          					<img src="<?php echo $img ?>image.jpg">
                          				</div>
                          				<div class="page-info">
                          					<a class="page-title" href="#"> page name<span class="pull-right ">Followers</span></a>
                          					<span class="page-description">description</span>
                          					
                          				</div>
                          			</li>
                          			<li class="item">
                          				<div class="page-img">
                          					<img src="<?php echo $img ?>image.jpg">
                          				</div>
                          				<div class="page-info">
                          					<a class="page-title" href="#"> page name<span class="pull-right ">Followers</span></a>
                          					<span class="page-description">description</span>
                          					
                          				</div>
                          			</li>
                          			<li class="item">
                          				<div class="page-img">
                          					<img src="<?php echo $img ?>image.jpg">
                          				</div>
                          				<div class="page-info">
                          					<a class="page-title" href="#"> page name<span class="pull-right ">Followers</span></a>
                          					<span class="page-description">description</span>
                          					
                          				</div>
                          			</li>

                          		</ul>
                          		
                          	</div>
                          	
                          </div>
		 			</div>
		 	
		 	</section>
	 </div>
	</div>   
</div>
<script type="text/javascript">
	function checkInput(e){
  //13 means enter
  if(e.keyCode === 13){

          var id_post = $('.input-comment').attr("data-id");
          var comment = $('.input-comment').val();
           $.post("manage.php?do=addcomment", { idpost : id_post , comment1:comment }).done(function(data) {
            var result = $.trim(data);
            console.log(result);
            console.log(id_post);
            if(result == "ok") {
              location.reload();
            }
          });
  }
} 
</script>
<script>
  function toggleComments(id) {
    $("#body-comment-box"+id).slideToggle("slow");
  }
</script>

 <?php include $tpl.'footer.php';
       include $tpl.'footer_end.php';
  ?>






<?php

}else{
	header('location:login.php');
	exit();
}

?>
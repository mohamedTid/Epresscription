<?php
//session_start();
if(true){
	$title='Profile';
	include 'init.php';

	if($_SERVER['REQUEST_METHOD']=='POST'){ //check if user  come from  post request
		$file='';
		if(!empty($_FILES['image']['name'])){
		$folder_dir= $img.'profile_img/'; //profile images directory 
		$base =basename($_FILES['image']['name']); //function  return the final name component of path(image: come from input field )
         $imageFileType=pathinfo($base, PATHINFO_EXTENSION); // return (jpg or png )
         $file=uniqid(). '.' .$imageFileType ; //return unique string with extantion (exp : 154snkqksqsn78ml.jpg)
         $fileName= $folder_dir . $file; // the path of the img exp: layout/image/profile_img/
         // check if file exist 
         if(file_exists($_FILES['image']['tmp_name'])){  //tmp_name= temporery location
         	      if( $imageFileType=='jpg' ||  $imageFileType=='png'){ //check from the format of the image 
         		   move_uploaded_file($_FILES['image']['tmp_name'], $fileName); // remove the image from temporery location to file directory
         	      }else{
         		 $_SESSION['uploadError'] = "wrong Format. Only jpg or png allowed.";   
         	      }

         }else{
            $_SESSION['uploadError'] = "something went wrong. File not uploaded";	
         }
         //select all fields to check if the profile image exist or no 
          $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	      $stmt->execute(array( $_SESSION['id']));
	       $rows= $stmt->fetch();
	       $result=$stmt->rowCount();
	      if($result > 0){  
	      	$row=$rows['profileimage']; 
	      	if($file!=''){ // there is allresy a picture
	      		unlink( $img.'profile_img/'.$row); // to delete image if it's exist in databade and 
	      	}
	      }
	  }else{
           $file=$_POST['new-img'];
	  }

	    //set fileds in variables
		  $name        =$_POST['name'];
		  $designation =$_POST['designation'];
		  $degree      =$_POST['degree'];
		  $university  =$_POST['university'];
		  $city        =$_POST['city'];
		  $country     =$_POST['country'];
		  $skills      =$_POST['skills'];
		  $aboutme     =$_POST['aboutme'];
		       //password trick
		         $pass=' ';
                 if(empty($_POST['new-password'])){
                     $pass=$_POST['old-password'];
                 }else{
                     $pass= sha1($_POST['new-password']);
                 }

		    //update tha database with new info
          $stmt=$con->prepare("UPDATE socialnetwork. users SET  name =?, password=?, designation=?, degree=?, university=?, city=?, country=?, skills=?, aboutme=?, profileimage=?  WHERE userid= ? ");
          $stmt->execute(array($name, $pass , $designation , $degree , $university, $city, $country, $skills, $aboutme, 
          	$file,  $_SESSION['id']));
		 $count= $stmt->rowCount();
		 
	}?>
	<?php 
	//get information from database to  display it in fields and update  it 
	   $name =$email= $designation=$degree=$university=$city=$country=$skills=$aboutme=$profileimage='';
	   //select all fields in database
        $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	    $stmt->execute(array( $_SESSION['id']));
	    $filed= $stmt->fetch();
         $count_row= $stmt->rowCount();
      //check if inforamation exist and display it in form fields
	  if($count_row>0){
	 	$name        =$filed['name'];
	 	$password    =$filed['password'];
	 	$email       =$filed['email'];
		$designation =$filed['designation'];
		$degree      =$filed['degree'];
		$university  =$filed['university'];
		$city        =$filed['city'];
		$country     =$filed['country'];
		$skills      =$filed['skills'];
		$aboutme     =$filed['aboutme'];
		$profileimage=$filed['profileimage'];
	 } ?>

<div class="dash bg-gris">
	<div class="dash1 ">
		<div class=" dash2  margin-pull-r">
		<section class="fd-head" ><h1 style="margin-left: 15px;">User profile</h1></section>
		<section class="contant">
			<div>
				<!-- =============== START USER INFORMATION FIELD =========================================== -->
				<div class="col3">
					<div class="box border-blue">
						<div class="box-body">
							 <?php 
							         $stmt = $con->prepare("SELECT * FROM socialnetwork. users  WHERE userid = ? ");
	                                 $stmt->execute(array( $_SESSION['id']));
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
							
							<h3 class="profile-user-name text-center"><?php echo  $name ;?></h3>
							<p class="text-center profile-p"><?php echo $designation;?></p>

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
							 <?php  if( $degree!='' && $country!=''){?>
							 <p class=" profile-p"> <?php echo $degree ; ?> from  <?php echo $university; ?></p>
							 <?php } ?>
							 <hr>
							 <strong><i class="fa fa-map-marker  "></i>  Location</strong>
							  <?php if ( $city!='' &&  $country!=''){?>
							 <p class=" profile-p"> <?php echo $city ; ?>, <?php echo $country;  ?></p>
							 <?php }?>

							 <hr>
							 <strong><i class="fa fa-pencil "></i> Skills</strong>
							 <p>
							 	<?php
							 	    //explode() function : to split or separete a string by another string 
							 	$arr=explode(",", $skills); //separete the skills each one by one 
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

                                  <p class="label profile-p" "><?php echo $aboutme ; ?></p>
							</div>
						</div>
						
					</div>
					
				</div> 
			<!-- =============== END USER INFORMATION FIELD =========================================== -->
                   <!-- =================menu fields================== -->
				<div class="col4" >
					<div class="box">
						<div class="nav-menu">
							<div class="min-menu min-menu-border" data-toggle="page1" >Activity</div>
							<div class="min-menu" data-toggle="page2">Settings</div> 
				       </div>
				   </div>
				</div>
			<!-- ===================================== START HOMEPAGE ========================================================== -->
				<div class="col4  menu-1 " id="page1">
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

		 							<?php unset($_SESSION['uploadError']);} ?> 
		 						</div>
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
		<!-- ===================================== END HOMEPAGE ========================================================== -->






		<!-- ====================== START SETTIND FORM ===========================================================  -->				
						<div class="col4  menu-1 hide-me" id="page2" >
							
							<div class=" box border-red tab-contant">
							     <form class="form-horizontal"action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">

				                  <div class="form-group">
				                    <label for="inputName" class=" control-label">Name</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="name" id="inputName" placeholder="Name" value="<?php echo $name ;?>" required>
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputEmail" class=" control-label">Email</label>

				                    <div class="inp-field">
				                     <input type="email" class="form-control" id="inputEmail" placeholder="Email" value="<?php echo $email ;?>" disabled>
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputEmail" class=" control-label">Password</label>

				                    <div class="inp-field">
				                    	<input type="hidden" name="old-password" class="form-control" value="<?php echo $password?>"  autocomplete="new-password">
				                      <input type="password" name="new-password" class="form-control" id="inputpassword" placeholder="Password" >
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputDesignation" class=" control-label">Designation</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="designation" id="inputDesignation" placeholder="Designation"  value="<?php echo $designation;?>">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputDegree" class=" control-label">Degree</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="degree" id="inputDegree" placeholder="Degree" value="<?php echo $degree;?>">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputUniversity" class=" control-label">University</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="university" id="inputUniversity" placeholder="University" value="<?php echo $university;?>">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputCity" class=" control-label">City</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="city" id="inputCity" placeholder="City" value="<?php echo $city ;?>">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputCountry" class=" control-label">Country</label>

				                    <div class="inp-field">
				                      <input type="text" class="form-control" name="country" id="inputCountry" placeholder="Country" value="<?php echo $country;?>">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputSkills" class=" control-label">Skills</label>
				                    <div class="inp-field">
				                      <textarea class="form-control" id="inputSkills" name="skills" placeholder="Skills (Separated with - expmle: Skill1 -Skill2 )" ><?php echo $skills;?></textarea>
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputAboutMe" class=" control-label">About Me</label>

				                    <div class="inp-field">
				                      <textarea class="form-control" id="inputAboutMe" name="aboutme" placeholder="About Me" ><?php echo $aboutme;?></textarea>
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label for="inputProfileImage" class=" control-label">Upload Profile Image</label>

				                    <div class="inp-field">
				                    	<input type="hidden" id="inputProfileNaw-Image" name="new-img" class="form-control" value="<?php echo $profileimage ?>">

				                      <input type="file" id="inputProfileImage" name="image" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <div class="btn-field inp-field">
				                      <button type="submit" class="submit-btn">Submit</button>
				                    </div>
				                  </div>
				                  
				                </form>
								
							</div>	
						</div>
			<!-- ====================== END SETTIND FORM ===========================================================  -->				
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
<?php
include $tpl.'footer.php';
include $tpl.'footer_end.php';
?>

<?php
}else{
	header('location:registre.php');
	exit();
}

?>
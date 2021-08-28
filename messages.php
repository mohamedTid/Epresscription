<?php
session_start();
if(isset($_SESSION['id'])){
	$title='Tidda.com';
       include 'init.php';
?>
<div class="dash bg-gris">
	<div class="dash1 ">
		<div class=" dash2  margin-pull-r">
			<section class="fd-head" ><h1 >Messages</h1></section>
			
           





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
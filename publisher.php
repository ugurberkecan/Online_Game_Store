<?php 
session_start();

# If not publisher ID is set
if (!isset($_GET['id'])) {
	header("Location: index.php");
	exit;
}

# Get publisher ID from GET request
$id = $_GET['id'];

# Database Connection File
include "db_conn.php";

# game helper function
include "php/func-game.php";
$games = get_games_by_publisher($conn, $id);

# publisher helper function
include "php/func-publisher.php";
$publishers = get_all_publisher($conn);
$current_publisher = get_publisher($conn, $id);


# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$current_publisher['name']?></title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">

</head>
<body style="background-image: url(https://wallpapercave.com/wp/wp2655401.jpg);"> 
	<div class="container">
		<nav class="navbar navbar-expand-lg">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="index.php" style="color: white;">Game Store</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link active" 
		             aria-current="page" 
		             href="index.php" style="color: white;">Store</a>
		        </li>
		        <li class="nav-item">
		          <?php if (isset($_SESSION['user_id'])) {?>
		          	<a class="nav-link" 
		             href="admin.php" style="color: white;">Admin</a>
		          <?php }else{ ?>
		          <a class="nav-link" 
		             href="login.php" style="color: white;">Login</a>
		          <?php } ?>

		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
		<h1 class="display-4 p-3 fs-3" style="color: white;"> 
			<a href="index.php"
			   class="nd">
			
			</a>
		   <?=$current_publisher['name']?>
		</h1>
		<div class="d-flex pt-3">
			<?php if ($games == 0){ ?>
				<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
			    There is no game in the database
		       </div>
			<?php }else{ ?>
			<div class="pdf-list d-flex flex-wrap", style="height: 500px;">
				<?php foreach ($games as $game) { ?>
				<div class="card m-1">
					<img src="uploads/cover/<?=$game['cover']?>"
					     class="card-img-top">
					<div class="card-body">
						<h5 class="card-title">
							<?=$game['title']?>
						</h5>
						<p class="card-text">
							<i><b>By:
								<?php foreach($publishers as $publisher){ 
									if ($publisher['id'] == $game['publisher_id']) {
										echo $publisher['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
							<?=$game['description']?>
							<br><i><b>Category:
								<?php foreach($categories as $category){ 
									if ($category['id'] == $game['category_id']) {
										echo $category['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
						</p>
						<div style="display :inline-flex">
								<h5 style="text-align:center;margin-top:5px"  class="card-title">
									<?= $game['price'] . "$" ?>
								</h5>
						
								<a style="margin-left:100px;padding-right:50px;background-color:steelblue" href="uploads/files/<?= $game['file'] ?>" class="btn btn-primary" download="<?= $game['title'] ?>">Buy	</a>
								
								</div>
					</div>
				</div>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="category">
			<!-- List of categories -->
			<div class="list-group">
				<?php if ($categories == 0){
					// do nothing
				}else{ ?>
				<a href="#"
				   class="list-group-item list-group-item-action active" style="background-color: steelblue;">Category</a>
				   <?php foreach ($categories as $category ) {?>
				  
				   <a href="category.php?id=<?=$category['id']?>"
				      class="list-group-item list-group-item-action">
				      <?=$category['name']?></a>
				<?php } } ?>
			</div>

			<!-- List of publishers -->
			<div class="list-group mt-5">
				<?php if ($publishers == 0){
					// do nothing
				}else{ ?>
				<a href="#"
				   class="list-group-item list-group-item-action active" style="background-color: steelblue;">Publishers</a>
				   <?php foreach ($publishers as $publisher ) {?>
				  
				   <a href="publisher.php?id=<?=$publisher['id']?>"
				      class="list-group-item list-group-item-action">
				      <?=$publisher['name']?></a>
				<?php } } ?>
			</div>
		</div>
		</div>
	</div>
</body>
</html>
<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# If game ID is not set
	if (!isset($_GET['id'])) {
		#Redirect to admin.php page
        header("Location: admin.php");
        exit;
	}

	$id = $_GET['id'];

	# Database Connection File
	include "db_conn.php";

	# game helper function
	include "php/func-game.php";
    $game = get_game($conn, $id);
    
    # If the ID is invalid
    if ($game == 0) {
    	#Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    # Category helper function
	include "php/func-category.php";
    $categories = get_all_categories($conn);

    # publisher helper function
	include "php/func-publisher.php";
    $publishers = get_all_publisher($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit game</title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">Admin</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link" 
		             aria-current="page" 
		             href="index.php">Store</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-game.php">Add game</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-category.php">Add Category</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-publisher.php">Add publisher</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="logout.php">Logout</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
     <form action="php/edit-game.php"
           method="post"
           enctype="multipart/form-data" 
           class="shadow p-4 rounded mt-5"
           style="width: 90%; max-width: 50rem;">

     	<h1 class="text-center pb-5 display-4 fs-3">
     		Edit game
     	</h1>
     	<?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
		<?php } ?>
     	<div class="mb-3">
		    <label class="form-label">
		           game Title
		           </label>
		    <input type="text" 
		           hidden
		           value="<?=$game['id']?>" 
		           name="game_id">

		    <input type="text" 
		           class="form-control"
		           value="<?=$game['title']?>" 
		           name="game_title">
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           game Description
		           </label>
		    <input type="text" 
		           class="form-control" 
		           value="<?=$game['description']?>"
		           name="game_description">
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           game publisher
		           </label>
		    <select name="game_publisher"
		            class="form-control">
		    	    <option value="0">
		    	    	Select publisher
		    	    </option>
		    	    <?php 
                    if ($publishers == 0) {
                    	# Do nothing!
                    }else{
		    	    foreach ($publishers as $publisher) { 
		    	    	if ($game['publisher_id'] == $publisher['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$publisher['id']?>">
		    	    	  <?=$publisher['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$publisher['id']?>">
							<?=$publisher['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           game Category
		           </label>
		    <select name="game_category"
		            class="form-control">
		    	    <option value="0">
		    	    	Select category
		    	    </option>
		    	    <?php 
                    if ($categories == 0) {
                    	# Do nothing!
                    }else{
		    	    foreach ($categories as $category) { 
		    	    	if ($game['category_id'] == $category['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$category['id']?>">
		    	    	  <?=$category['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$category['id']?>">
							<?=$category['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           game Cover
		           </label>
		    <input type="file" 
		           class="form-control" 
		           name="game_cover">

		     <input type="text" 
		           hidden
		           value="<?=$game['cover']?>" 
		           name="current_cover">

		    <a href="uploads/cover/<?=$game['cover']?>"
		       class="link-dark">Current Cover</a>
		</div>
		<div class="mb-3">
		    <label class="form-label">
		           Price
		           </label>
		    <input type="text" 
		           class="form-control" 
		           value="<?=$game['price']?>"
		           name="game_price">
		</div>
	    <button type="submit" 
	            class="btn btn-primary">
	            Update</button>
     </form>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>
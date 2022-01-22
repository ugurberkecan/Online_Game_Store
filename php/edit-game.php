<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../db_conn.php";

    # Validation helper function
    include "func-validation.php";

    # File Upload helper function
    include "func-file-upload.php";


    /** 
	  If all Input field
	  are filled
	**/
	if (isset($_POST['game_id'])          &&
        isset($_POST['game_title'])       &&
        isset($_POST['game_description']) &&
        isset($_POST['game_publisher'])      &&
        isset($_POST['game_category'])    &&
        isset($_FILES['game_cover'])      &&
        isset($_FILES['file'])            &&
        isset($_POST['current_cover'])    &&
        isset($_POST['current_file'])) {


		$id          = $_POST['game_id'];
		$title       = $_POST['game_title'];
		$description = $_POST['game_description'];
		$publisher      = $_POST['game_publisher'];
		$category    = $_POST['game_category'];


        $current_cover = $_POST['current_cover'];
        $current_file  = $_POST['current_file'];

        #simple form Validation
        $text = "Game title";
        $location = "../edit-game.php";
        $ms = "id=$id&error";
		is_empty($title, $text, $location, $ms, "");

		$text = "Game description";
        $location = "../edit-game.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Game publisher";
        $location = "../edit-game.php";
        $ms = "id=$id&error";
		is_empty($publisher, $text, $location, $ms, "");

		$text = "Game category";
        $location = "../edit-game.php";
        $ms = "id=$id&error";
		is_empty($category, $text, $location, $ms, "");

   
          if (!empty($_FILES['game_cover']['name'])) {
          	 
		      if (!empty($_FILES['file']['name'])) {
		      
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $game_cover = upload_file($_FILES['game_cover'], $allowed_image_exs, $path);


		        $allowed_file_exs = array("pdf", "docx", "pptx","jpg","png","jpeg");
		        $path = "files";
		        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
                
            
		        if ($game_cover['status'] == "error" || 
		            $file['status'] == "error") {

			    	$em = $game_cover['data'];

			    	header("Location: ../edit-game.php?error=$em&id=$id");
			    	exit;
			    }else {
             
			      $c_p_game_cover = "../uploads/cover/$current_cover";


			      $c_p_file = "../uploads/files/$current_file";

			
			      unlink($c_p_game_cover);
			      unlink($c_p_file);

			      /**
		              Getting the new file name 
		              and the new game cover name 
		          **/
		           $file_URL = $file['data'];
		           $game_cover_URL = $game_cover['data'];

		            # update just the data
		          	$sql = "UPDATE games
		          	        SET title=?,
		          	            publisher_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?,
		          	            file=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $publisher, $description, $category,$game_cover_URL, $file_URL, $id]);

				  
				     if ($res) {
				     	# success message
				     	$sm = "Successfully updated!";
						header("Location: ../edit-game.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# Error message
				     	$em = "Unknown Error Occurred!";
						header("Location: ../edit-game.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }else {
		     
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $game_cover = upload_file($_FILES['game_cover'], $allowed_image_exs, $path);
                
        
		        if ($game_cover['status'] == "error") {

			    	$em = $game_cover['data'];

			    	header("Location: ../edit-game.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # current game cover path
			      $c_p_game_cover = "../uploads/cover/$current_cover";

			      unlink($c_p_game_cover);

		           $game_cover_URL = $game_cover['data'];


		          	$sql = "UPDATE games
		          	        SET title=?,
		          	            publisher_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $publisher, $description, $category,$game_cover_URL, $id]);

				     if ($res) {
				     	# success message
				     	$sm = "Successfully updated!";
						header("Location: ../edit-game.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# Error message
				     	$em = "Unknown Error Occurred!";
						header("Location: ../edit-game.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }
          }
    
          else if(!empty($_FILES['file']['name'])){
          	# update just the file
            
            # game cover Uploading
	        $allowed_file_exs = array("pdf", "docx", "pptx");
	        $path = "files";
	        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
 
	        if ($file['status'] == "error") {

		    	$em = $file['data'];

		    	header("Location: ../edit-game.php?error=$em&id=$id");
		    	exit;
		    }else {
              # current game cover path
		      $c_p_file = "../uploads/files/$current_file";

		      # Delete from the server
		      unlink($c_p_file);

	           $file_URL = $file['data'];

	            # update just the data
	          	$sql = "UPDATE games
	          	        SET title=?,
	          	            publisher_id=?,
	          	            description=?,
	          	            category_id=?,
	          	            file=?
	          	        WHERE id=?";
	          	$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$title, $publisher, $description, $category, $file_URL, $id]);

	
			     if ($res) {
			     	# success message
			     	$sm = "Successfully updated!";
					header("Location: ../edit-game.php?success=$sm&id=$id");
		            exit;
			     }else{
			     	# Error message
			     	$em = "Unknown Error Occurred!";
					header("Location: ../edit-game.php?error=$em&id=$id");
		            exit;
			     }


		    }
	      
          }else {
          	# update just the data
          	$sql = "UPDATE games
          	        SET title=?,
          	            publisher_id=?,
          	            description=?,
          	            category_id=?
          	        WHERE id=?";
          	$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$title, $publisher, $description, $category, $id]);

		    /**
		      If there is no error while 
		      updating the data
		    **/
		     if ($res) {
		     	# success message
		     	$sm = "Successfully updated!";
				header("Location: ../edit-game.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Error message
		     	$em = "Unknown Error Occurred!";
				header("Location: ../edit-game.php?error=$em&id=$id");
	            exit;
		     }
          } 
	}else {
      header("Location: ../admin.php");
      exit;
	}

}else{
  header("Location: ../login.php");
  exit;
}
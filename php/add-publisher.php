<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../db_conn.php";

	if (isset($_POST['publisher_name'])) {
		$name = $_POST['publisher_name'];
		if (empty($name)) {
			$em = "The publisher name is required";
			header("Location: ../add-publisher.php?error=$em");
            exit;
		}else {
			$sql  = "INSERT INTO publishers (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);
		     if ($res) {
		     	# success message
		     	$sm = "Successfully created!";
				header("Location: ../add-publisher.php?success=$sm");
	            exit;
		     }else{
		     	# Error message
		     	$em = "Unknown Error Occurred!";
				header("Location: ../add-publisher.php?error=$em");
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
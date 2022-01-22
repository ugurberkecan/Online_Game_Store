<?php
session_start();

if (
	isset($_SESSION['user_id']) &&
	isset($_SESSION['user_email'])
) {
	include "../db_conn.php";
	include "func-validation.php";
	include "func-file-upload.php";

	if (
		isset($_POST['game_title']) &&
		isset($_POST['game_description']) &&
		isset($_POST['game_publisher']) &&
		isset($_POST['game_category']) &&
		isset($_FILES['game_cover']) &&
		isset($_POST['game_price'])
	) {
	
		$title = $_POST['game_title'];
		$description = $_POST['game_description'];
		$publisher = $_POST['game_publisher'];
		$category = $_POST['game_category'];
		$price = $_POST['game_price'];

		$user_input = 'title=' . $title . '&category_id=' . $category . '&desc=' . $description . '&publisher_id=' . $publisher;

		$text = "game title";
		$location = "../add-game.php";
		$ms = "error";
		is_empty($title, $text, $location, $ms, $user_input);

		$text = "game description";
		$location = "../add-game.php";
		$ms = "error";
		is_empty($description, $text, $location, $ms, $user_input);

		$text = "game publisher";
		$location = "../add-game.php";
		$ms = "error";
		is_empty($publisher, $text, $location, $ms, $user_input);

		$text = "game category";
		$location = "../add-game.php";
		$ms = "error";
		is_empty($category, $text, $location, $ms, $user_input);

		$text = "game price";
		$location = "../add-game.php";
		$ms = "error";
		is_empty($price, $text, $location, $ms, $user_input);

		# game cover Uploading
		$allowed_image_exs = array("jpg", "jpeg", "png");
		$path = "cover";
		$game_cover = upload_file($_FILES['game_cover'], $allowed_image_exs, $path);

		if ($game_cover['status'] == "error") {
			$em = $game_cover['data'];

			header("Location: ../add-game.php?error=$em&$user_input");
			exit;
		} else {

			if ($file['status'] == "error") {
				$em = $file['data'];
				header("Location: ../add-game.php?error=$em&$user_input");
				exit;
			} else {

				$game_cover_URL = $game_cover['data'];
				$sql = "INSERT INTO games (title,
                                            publisher_id,
                                            description,
                                            category_id,
                                            cover,
                                            price)
                         VALUES (?,?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$res = $stmt->execute([$title, $publisher, $description, $category, $game_cover_URL, $price]);

				if ($res) {
					# success message
					$sm = "The game successfully created!";
					header("Location: ../add-game.php?success=$sm");
					exit;
				} else {
					# Error message
					$em = "Unknown Error Occurred!";
					header("Location: ../add-game.php?error=$em");
					exit;
				}
			}
		}
	} else {
		header("Location: ../admin.php");
		exit;
	}
} else {
	header("Location: ../login.php");
	exit;
}

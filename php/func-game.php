<?php  

# Get All games function
function get_all_games($con){
   $sql  = "SELECT * FROM games ORDER bY id DESC";
   $stmt = $con->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() > 0) {
   	  $games = $stmt->fetchAll();
   }else {
      $games = 0;
   }

   return $games;
}



# Get  game by ID function
function get_game($con, $id){
   $sql  = "SELECT * FROM games WHERE id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
   	  $game = $stmt->fetch();
   }else {
      $game = 0;
   }

   return $game;
}


# Search games function
function search_games($con, $key){
   # creating simple search algorithm :) 
   $key = "%{$key}%";

   $sql  = "SELECT * FROM games 
            WHERE title LIKE ?
            OR description LIKE ?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$key, $key]);

   if ($stmt->rowCount() > 0) {
        $games = $stmt->fetchAll();
   }else {
      $games = 0;
   }

   return $games;
}

# get games by category
function get_games_by_category($con, $id){
   $sql  = "SELECT * FROM games WHERE category_id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
        $games = $stmt->fetchAll();
   }else {
      $games = 0;
   }

   return $games;
}


# get games by publisher
function get_games_by_publisher($con, $id){
   $sql  = "SELECT * FROM games WHERE publisher_id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
        $games = $stmt->fetchAll();
   }else {
      $games = 0;
   }

   return $games;
}
<?php 

# Get all Author function
function get_all_publisher($con){
   $sql  = "SELECT * FROM publishers";
   $stmt = $con->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() > 0) {
   	  $publishers = $stmt->fetchAll();
   }else {
      $publishers = 0;
   }

   return $publishers;
}


# Get  Author by ID function
function get_publisher($con, $id){
   $sql  = "SELECT * FROM publishers WHERE id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
   	  $author = $stmt->fetch();
   }else {
      $author = 0;
   }

   return $author;
}
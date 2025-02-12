<?php  
session_start();  
include('./conn/db_connection.php');  
  
$group_id = $_POST['group_id'];  
  
try {  
   // Delete members of the group  
   $query = "DELETE FROM members WHERE group_id = ?";  
   $stmt = $conn->prepare($query);  
   $stmt->bind_param('i', $group_id);  
   if (!$stmt->execute()) {  
      throw new Exception($stmt->error);  
   }  
   $stmt->close();  
  
   // Delete the group  
   $query = "DELETE FROM `groups` WHERE group_id = ?"; // Enclose the table name in backticks  
   $stmt = $conn->prepare($query);  
   $stmt->bind_param('i', $group_id);  
   if (!$stmt->execute()) {  
      throw new Exception($stmt->error);  
   }  
   $stmt->close();  
  
   echo 'success';  
} catch (Exception $e) {  
   echo 'error: ' . $e->getMessage();  
}  
?>

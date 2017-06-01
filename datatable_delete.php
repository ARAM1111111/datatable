<?php
$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');

if(isset($_POST['user_id']))
{
	$stmt = $conn->prepare("DELETE FROM crud1 WHERE id= :id");
	$result = $stmt->execute([':id'=>$_POST['user_id']]);
}
echo "DATA DELETED";




?>
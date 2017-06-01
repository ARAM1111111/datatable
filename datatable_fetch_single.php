<?php
$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');
//print_r($_POST);
if(isset($_POST['user_id']))
{
	$output = array();
	$stmt = $conn->prepare("
		SELECT * FROM crud1
		WHERE id='".$_POST['user_id']."' LIMIT 1
	");
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach ($result as $key => $row) {
		$output['name'] = $row['name'];
		$output['lname'] = $row['lname'];
		if($row['img'] != ''){
			$output['user_img'] = '<img src="img/'.$row['img'].'" class="img-thumbnail" width="50" height="35"/>
				<input type="hidden" name="hidden_user_image" value="'.$row["img"].'" />';		
		}
		else
		{
			 $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
		}
	}

echo json_encode($output);
}





?>
<?php
$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');

function get_total_all(){
	$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');
	$stmt = $conn->prepare("SELECT * FROM crud1");
	$stmt->execute();
	return $stmt->rowCount();
}

function upload_image()
{
	$ext = explode('.',$_FILES['user_image']['name']);
	$new_name = rand().".".$ext[1];
	$file = "img/".$new_name;
	move_uploaded_file($_FILES['user_image']['tmp_name'], $file);
	return $new_name;
}

if(isset($_POST['operation']))
{

	if($_POST['operation'] == 'Add')
	{

		$image = '';
		if($_FILES['user_image']['name'] !='')
		{
			$image = upload_image();
		}
		$stmt = $conn->prepare('
			INSERT INTO crud1(name,lname,img)
			VALUES (:name,:lname,:image)	
		');
		$result = $stmt->execute([
			":name"=>$_POST['name'],
			":lname"=>$_POST['lname'],
			":image"=>$image
		]);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
		
	}

	if($_POST['operation'] == "Edit")
	{
		$image = upload_image();
	}
	else
	{
		$image = $_POST['hidden_user_image'];
	}
	$stmt = $conn->prepare(
		"UPDATE crud1
		SET name = :name, lname= :lname,img= :image
		WHERE id = :id"
	);
	print_r($_POST);
	$stmt->execute([
		':name'  =>	$_POST['name'],
		':lname' =>	$_POST['lname'],
		':image' =>	$image,
		':id'    =>	$_POST['user_id']
	]);
	echo "Data Updated";
}

?>
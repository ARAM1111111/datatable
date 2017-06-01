<?php
$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');

function get_total_all(){
	$conn = new PDO("mysql:host=localhost;dbname=colibri",'root','');
	$stmt = $conn->prepare("SELECT * FROM crud1");
	$stmt->execute();
	return $stmt->rowCount();
}


$query = "";
$output = array();
$query.=" SELECT * FROM crud1";

if(isset($_POST['search']['value']))
{
	$query.=' WHERE name LIKE "%'.$_POST['search']['value'].'%" ';
	$query.='OR lname LIKE "%'.$_POST['search']['value'].'%"';
}

if(isset($_POST["order"]))
{
	$query.=' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].'  ';
}
else{
	$query.=" ORDER BY id DESC";
}

if($_POST["length"] != -1)
{
	$query .= ' LIMIT '.$_POST['start'].', '.$_POST['length'];
}

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll();



$data = array();
$filtered_rows = $stmt->rowCount();
foreach ($result as  $row) {

	$img = '';
	if($row['img']!=''){
		$img = '<img src="img/'.$row['img'].'" class="img-thumbnail" width="50"  height="35"/>';
	}
	else{
		$img = "";
	}
	$sub_array = array();
	$sub_array[] = $img;
	$sub_array[] = $row['name'];
	$sub_array[] = $row['lname'];
	$sub_array[] = '<button type="button" name="update" id="'.$row['id'].'" class="btn btn-warning btn-xs update">UPDATE</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row['id'].'" class="btn btn-danger btn-xs delete">DELETE</button>';
	$data[] = $sub_array;
}

// echo "<pre>";
// print_r($data);




$output = array( 
	'draw' 				=> intval($_POST["draw"]),
	'recordsTotal' 		=> $filtered_rows,
	'recordsFiltered'	=>get_total_all(),
	'data' 				=>$data
);


 echo json_encode($output);



?>
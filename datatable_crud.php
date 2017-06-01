<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
				<!-- DATATABLE INCLUD -->
	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
	body{
		margin: 0;
		padding: 0;
		background-color:#f1f1f1;
	}
	.box{
		/*width: 1270px;*/
		padding: 20px;
		background-color: #fff;
		border:1px solid #ccc;
		border-radius: 5px;
		margin-top: 25px;
	}
</style>
	<title></title>
</head>
<body>
	<div class="container box">
		<h1 align="center">Ajax crud with datatable</h1><br/>
		<div align="right">
			<button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">
				ADD
			</button>
		</div>
		<div class="table-responsive">
			<table id="user_data" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th width="10%">Image</th>
						<th width="35%">Name</th>
						<th width="35%">Lname</th>
						<th width="10%">Edit</th>
						<th width="10%">Delete</th>              
					</tr>
				</thead>
			</table>
		</div>
	</div>

<div id="userModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="user_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add User</h4>
				</div>
				<div class="modal-body">
					<label for="#name">Enter First Name</label>
					<input type="text" name="name" id="name" class="form-control"/>
					<br/>
					<label for="#lname">Enter Last Name</label>
					<input type="text" name="lname" id="lname" class="form-control"/>
					<br/>
					<label for="">Select User Image</label>
					<input type="file" name="user_image" id="user_image"/>
					<span id="user_uploaded_image"></span>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="user_id" id="user_id">
					<input type="hidden" name="operation" id="operation" value="Add">
					<input type="submit" name="action" id="action" class="btn btn-success" value="Add">
				</div>
			</div>
		</form>
	</div>
</div>


<script>
$(document).ready(function(){
$('#add_button').click(function(){
		$('#user_form')[0].reset();
		$('.modal-title').text("Add User");
		$('#action').val("Add");
		$('#operation').val("Add");
		$('#user_uploaded_image').html('');
	});

var dataTable = $("#user_data").DataTable({
	"processing":true,
	"serverSide":true,
	"order":[],
	"ajax":{
		url:"datatable_fetch.php",
		type:"POST"
	},
	"columnDefs":[{
		"targets":[0,3,4],
		"orderable":false,
	},],
})



$(document).on('submit','#user_form',function(e){
	e.preventDefault();
	var name = $("#name").val();
	var lname = $("#lname").val();
	var extension = $("#user_image").val().split('.').pop().toLowerCase();
	if(extension != '')
	{
		if(jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1)
		{
			alert("Invaled Image File");
			$("#user_image").val('');
			return false;
		}
	}
	if(name !="" && lname != '')
	{
		$.ajax({
			url:'datatable_insert.php',
			type:"POST",
			data:new FormData(this),
			contentType:false,
			processData:false,
			success:function(d)
			{
				$("#user_form")[0].reset();
				$("#userModal").modal('hide');
				dataTable.ajax.reload();
				console.log(d);
				//alert(d);
			}

		});
	}
	else
	{
		alert("Both fields are Required");
	}
});

$(document).on("click",'.update',function(){

	var user_id = $(this).attr("id");
	$.ajax({
		url:"datatable_fetch_single.php",
		type:"POST",
		data:{user_id:user_id},
		dataType:"json",
		success:function(d)
		{
			console.log(d);
			$('#userModal').modal('show');
			$('#name').val(d.name);
			$('#lname').val(d.lname);
			$(".modal-title").text("EDIT USER");
			$('#user_id').val(user_id);
			$("#user_uploaded_image").html(d.user_img);
			$("#action").val("Edit");
			$('#operation').val("Edit");
		}
	});
});

$(document).on('click','.delete',function(){
	
	var user_id = $(this).attr("id");
	if(confirm('Are you sure delete???'))
	{
		$.post('datatable_delete.php', {user_id: user_id}, function(d){
			alert(d);
			dataTable.ajax.reload();
		});
	}else return false;
})


})	
</script>
</body>
</html>
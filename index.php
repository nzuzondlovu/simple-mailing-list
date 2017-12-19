<?php

$con = mysqli_connect('localhost', 'root', '', 'mailinglist');


// Send on email and save data to db
if (isset($_POST['submit'])) {

	$name = mysqli_real_escape_string($con, strip_tags(trim($_POST['name'])));
	$email = mysqli_real_escape_string($con, strip_tags(trim($_POST['email'])));
	$message = mysqli_real_escape_string($con, strip_tags(trim($_POST['message'])));

	if ($name != '' && $email != '' && $message != '') {
		
		$sql = "INSERT INTO user(name, email) VALUES('".$name."', '".$email."')";
		mysqli_query($con, $sql);

		$subject = 'Contact Form';
		$to = 'info@example.com';

		$headers = "MIME-Version: 1.0"."\r\n";
		$headers .= "Content-type: text/html; charset = UTF-8"."\r\n";
		$headers .= "From: ".$email."\r\n";

		mail($to, $subject, $message, $headers);

	} else {
		echo "Please fill in all fields";
	}
	
}

//Send emails to the mailing list users
if (isset($_POST['listbtn'])) {

	$msg = mysqli_real_escape_string($con, strip_tags(trim($_POST['listmsg'])));

	if ($msg != '') {
		
		$sql = "SELECT * FROM user";
		$res = mysqli_query($con, $sql);

		while ($row = mysqli_fetch_assoc($res)) {

			$subject = 'Mailing List';
			$to = $row['email'];
			$message = $row['name']. "\n\n".$msg;

			$headers = "MIME-Version: 1.0"."\r\n";
			$headers .= "Content-type: text/html; charset = UTF-8"."\r\n";
			$headers .= "From: list@example.com"."\r\n";

			mail($to, $subject, $message, $headers);

			//To see the results just upload the script to the server

		}

	} else {
		
		echo "Please fill in the mailing list message";
	}
	

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Simple Mailing List</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="datatables/css/jquery.dataTables.min.css">
</head>
<body>
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<h1>Send Your Message</h1>
				<form method="POST">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="name" id="name" class="form-control" required="required">
					</div>					
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" name="email" id="email" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label for="message">Message</label>
						<textarea class="form-control" name="message" id="message" required="required"></textarea>
					</div>
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
			<hr>
			<div class="col-md-offset-2 col-md-8">
				<?php

				$sql = "SELECT * FROM user";
				$res = mysqli_query($con, $sql);

				if (mysqli_num_rows($res) > 0) {
					
					echo '
					<table id="table" class="table datatable">
					<thead>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Date</th>
					</thead>
					<tbody>';

					while ($row = mysqli_fetch_assoc($res)) {
						
						echo '
						<tr>
						<td>'.$row['id'].'</td>
						<td>'.$row['name'].'</td>
						<td>'.$row['email'].'</td>
						<td>'.$row['date'].'</td>
						</tr>';
					}

					echo '
					</tbody>
					</table>';
				} else {
					
					echo 'Please enter new users';
				}
				?>
			</div>
			<div class="col-md-offset-2 col-md-8">
				<form method="POST">
					<div class="form-group">
						<label for="listmsg">Message for mailing List</label>
						<textarea class="form-control" name="listmsg" id="listmsg" required="required"></textarea>
					</div>
					<button type="submit" name="listbtn" class="btn btn-warning">Send Mail</button>
				</form>
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script>
		$(document).ready(function(){
			$('#table').DataTable();
		});
	</script>
</body>
</html>
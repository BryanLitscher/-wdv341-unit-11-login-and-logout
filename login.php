<?php
require 'dbConnect.php';
//https://www.youtube.com/watch?v=O0Ky0tKvsJ8
$signedIn = false;
$loginError = "";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	//echo "<pre>";
	//print_r($_SESSION);
	//echo "</pre>";
	if (isset($_POST['uname'])){
		
		try {
			$stmt = $conn->prepare("SELECT 
				COUNT(*) as rows 
				FROM event_user 
				WHERE event_user_name = '" . $_POST["uname"] . "'" 
				. "AND event_user_password = '" . $_POST["password"] . "'" 
				) ;
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$result = $stmt->fetch();  // associative array
			$totalQuantityUsers = $result["rows"];
			if( $totalQuantityUsers > 0) {
				$_SESSION['uname'] = $_POST["uname"];
				$_SESSION['validUser'] = true;
			}else{
				$loginError = "Invalid Login";
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}
}

if ($_SESSION['validUser']??false){
	$signedIn = true;
}



?>


<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>WDV321 Advanced Javascript Unit 6</title>
		<style>
			body{background-color:linen}
			#formbody{
				width:50%;
				border:thin solid black;
				margin:auto;
				padding:50px;
			}
			form{margin:auto;}
			.error{color:red;font-style:italic}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- <script src="scripts.js"></script> -->
	<script>
	$(document).ready(
		function(){
			//code after page loads
		}
	)
	
	</script>	
	


	</head>

	<body>
<?php if (!$signedIn){ ?>
	<div id="formbody">
		<form method="POST" action=<?php htmlspecialchars($_SERVER["PHP_SELF"])?>>
			<p>
			<label for="username">User name:</label>
			<input name="uname" id="username"> 
			</p>	
			<p>
			<label for="password">Password:</label>
			<input type="password" name="password" id="username"> 
			</p>
			<input type=submit>
		</form>
		<p class="error"><?php echo $loginError;?>
	</div>
<?php }else{ ?>
	<div id="formbody">
		<form method="POST" action=<?php htmlspecialchars($_SERVER["PHP_SELF"])?>>
			<p>
				<label for="action">Choose action:</label>

				<select name="action" id="action">
				  <option value="addnewevent">Add New Event</option>
				  <option value="showalistofevents">Show a list of events.</option>
				  <option value="logout">Logout of Administrator</option>
				</select>
			</p>	
			<input type=submit>
		</form>
		<p class="error"><?php echo $loginError;?>
	</div>

<?php } ?>

		<form method="POST" action="logout.php">
			<input name="killsession" type=submit value="Log Out">
		</form>

	</body>
	
	


</html>

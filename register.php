<?php
session_name ('admin');
session_start(); // Start the session.

$page_title = 'Enter Salesman Information';
include('./includes_css/header_new.html');

if (!isset($_SESSION['user_id'])) {

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	$url .= '/login.php'; // Add the page.
	header("Location: $url");
	exit(); // Quit the script.
}

$target = "images/".basename($_FILES['image']['name']); //SQL Injection defence!

if(isset($_POST['submitted'])){
	$errors = array();
	
	$di = trim($_POST['dealer_id']);
	$image = $_FILES['image']['name'];
	$text = $_POST['picname'];
	
	if(empty($_POST['salesman_id'])){
		$errors[] = 'You forgot to enter your id';
		} else {
			$si = trim($_POST['salesman_id']);
		}	
	if(empty($_POST['salesman_name'])){
		$errors[] = 'You forgot to enter your name';
		} else {
			$sn = trim($_POST['salesman_name']);
		}	
			
	if (empty($_POST['salesman_phone'])){
		$errors[] = 'You forgot to enter your phone';
		} else {
			$sp = trim($_POST['salesman_phone']);
			}	
			
	if (empty($errors)){
		require_once ('db_connect.php');
		
		$query = "SELECT salesman_id FROM salesman WHERE salesman_id='$si'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {
			$query = "INSERT INTO salesman (salesman_id, salesman_name, salesman_phone, dealer_id, image) VALUES
			('$si', '$sn', '$sp', '$di', '$image')";
			$result = @mysql_query ($query);
			
			if (move_uploaded_file($_FILES['image']['tmp_name'],$target)) {
			echo "<script>alert('Photo Uploaded Succesfully');MyWindow=window.close()</script>";
		}
		else {
			echo "<script>alert('Problem while uploading!');document.location.href='salesmanForm.php';</script>";
		}
	
		if ($result){
			echo '<h1 id="mainhead">Success</h1>
			<p>Your information has been saved!</p><p><br/></p>';
		
			exit();
		} else {
			echo '<h1 id="mainhead">Error</h1>
			<p>Your information unable to be saved</p><p><br/></p>';
			echo '<p>'.mysql_error().'<br/><br/>Query : '.$query.'</p>';
		
			exit();
		}
	}
	else { // Already registered.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The ID is already in use</p>';
		}
	
	mysql_close();
	} else {
		
		echo '<h1 id="mainhead">Error</h1>
		<p class="error">The following error(s) occurred : <br/>';
		foreach ($errors as $msg){
			echo " - $msg<br/>\n";
		}
		echo '</p><p>Please try again</p><p><br/></p>';
	}
}
?>
<body>
<form method='post' action= 'register.php' enctype="multipart/form-data">

<?php
require_once('db_connect.php');
$sql1 = "SELECT d.dealer_id, d.dealer_name FROM dealership d";
$result1 = @mysql_query($sql1);
?>

	<table width='400' border='5' align='center'>
	<tr>
		<td align='center' colspan='5'><h1>Salesman Info</h1></td>
	</tr>
	
	<tr>
		<td align='center'>Salesman ID:</td>
		<td><input type='text' name='salesman_id' size="20" maxlength="20"
		value="<?php if (isset($_POST['salesman_id'])) echo $_POST['salesman_id']; ?>" /></td>
	</tr>

	<tr>
		<td align='center'>Salesman Name:</td>
		<td><input type='text' name='salesman_name' size="20" maxlength="20"
		value="<?php if (isset($_POST['salesman_name'])) echo $_POST['salesman_name']; ?>"/></td>
	</tr>
	
	<tr>
		<td align='center'>Salesman Phone:</td>
		<td><input type='text' name='salesman_phone' size="20" maxlength="20"
		value="<?php if (isset($_POST['salesman_phone'])) echo $_POST['salesman_phone']; ?>"/></td>
	</tr>
	
	<tr>
		<td align='center'>Dealership:</td>
		<td><?php echo "<select name = dealer_id >Dealership Name</option>";
		while ($row = mysql_fetch_array($result1)) {
			echo "<option value=$row[dealer_id]>$row[dealer_name]</option>";
			} echo "</select>" ?></td>
	</tr>
	
	<tr>
		<td align='center'>Salesman Photo:</td>
		<td><input type="file" name="image" /></td>
	</tr>
	
	<tr>
		<td colspan='5' align='center'><input type='submit' name='submit'
		value='Add' /></td>
		<input type="hidden" name="submitted" value="TRUE"/>
	</tr>
	
	
	</table>
</form>
</body>
<?php
include('./includes_css/footer.html');
?>

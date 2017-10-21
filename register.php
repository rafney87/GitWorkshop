<?php
session_name ('admin');
session_start(); // Start the session.

$page_title = 'Enter Dealer Information';
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

if(isset($_POST['submitted'])){
	$errors = array();
	
	if(empty($_POST['id'])){
		$errors[] = 'You forgot to enter id';
		} else {
			$i = trim($_POST['id']);
		}	
	
	if(empty($_POST['username'])){
		$errors[] = 'You forgot to enter username';
		} else {
			$u = trim($_POST['username']);
		}	
			
	if (empty($_POST['password'])){
		$errors[] = 'You forgot to enter password';
		} else {
			$p = trim($_POST['password']);
			}
			
			
	if (empty($errors)){
		require_once ('db_connect.php');
		
		$query = "SELECT id FROM users WHERE id='$i'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0){
			$query = "INSERT INTO users (id, username, password) VALUES
			('$i', '$u', '$p')";
			$result = @mysql_query ($query);
	
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
	} else { // Already registered.
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
<form method='post' action= 'register.php'>
	<table width='400' border='5' align='center'>
	<tr>
		<td align='center' colspan='5'><h1>Dealer Form</h1></td>
	</tr>
	

	<tr>
		<td align='center'>Username:</td>
		<td><input type='text' name='username' size="20" maxlength="20"
		value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"/></td>
	</tr>
	
	<tr>
		<td align='center'>Password:</td>
		<td><input type='password' name='password' size="20" maxlength="20"
		value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"/></td>
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

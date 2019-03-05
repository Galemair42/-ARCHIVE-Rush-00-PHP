<?PHP
include "install.php";

function exit_error($error)
{
	echo "<p>$error<p>";
	exit ();
}

/* OPEN CONNECTION AND SESSION*/

$servername = "127.0.0.1";
$username = "root";
$password = "root42";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn)
		die("Connection failed: " . mysqli_connect_error());
mysqli_query($conn, "USE db_parashop");

/* RETRIEVE USERNAME AND PASSWD */

if (isset($_POST["user"])) {
	
	if (!isset($_POST["user"]) && !isset($_POST["passwd"]))
		exit_error("It would work better with a filled for");
	$username = $_POST["user"];
	$passwd = hash("whirlpool", $_POST["passwd"]);
	
	/* CHECK USERNAME DUPLICITY */
	
	$sql = "SELECT * FROM users WHERE username='".$username."'";
	if (($mysqli_result = mysqli_query($conn, $sql)) == FALSE)
		exit_error("Request to Data Base Failed");
	if (mysqli_num_rows($mysqli_result) > 0)
		exit_error("Sorry, this username is already taken");
	
	/* ADD USER TO DATA_BASE */
	
	$sql = "INSERT INTO users (username, passwd) VALUES('".$username."', '".$passwd."')";
	if (mysqli_query($conn, $sql) === FALSE)
		exit_error("Request to Data Base Failed");
}
?>
<html>
	<head>
		<link rel="stylesheet" href="index.css">
		<link rel="stylesheet" href="login.css">
		<link rel="shortcut icon" href="resources/parachute.png">
		<title>Parashop</title>
	</head>
	<body>
		<div id="header">
			<a href="."><img id="logo" src="resources/logo.png"></a>
			<ul id="menu">
				<li><a href="#">Shopping Cart</a></li>
				<li><a href="login.php">Login</a></li>
				<li id="current"><a href="create.php">Create new Account</a></li>
				<?PHP if ($_SESSION["admin"] === 1)
					echo "<li><a href=\"admin.php\">Admin</a></li>";
				?>
			</ul>
		</div>
		<div id="body">
			<div id="form">
				<form action="create.php" method="post">
					<h1>New Account</h1>
					<div id="login">
						<label for="username">Username</label>
						<input type="text" name="user" placeholder="Enter username..." required>
						<label for="password">Password</label>
						<input type="password" name="passwd" placeholder="Enter password..." required>
						<input id="button" type="submit" name="submit" value="OK">
					</div>
				</form>
			</div>
		</div>
	</body>
</html>

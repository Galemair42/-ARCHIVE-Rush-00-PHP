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
	$username = htmlspecialchars($_POST["user"]);
	$passwd = hash("whirlpool", $_POST["passwd"]);
	
	/* CHECK IF USERNAME AND PASSWD MATCH */
	
	$qry = ('SELECT * FROM users WHERE username = "'.$username.'" AND passwd = "'.$passwd.'"');
	if (($mysqli_result = mysqli_query($conn, $qry)) == FALSE)
		exit_error("Request to Data Base failed");
	if (mysqli_num_rows($mysqli_result) > 0)
	{
		$_SESSION["loggued_on_user"] = $username;
		$sql = "SELECT admin FROM users WHERE username ='". $username ."'";
		if (($res = mysqli_query($conn, $sql)) === 1)
			$_SESSION["admin"] = 1;
		header ("Location: .");
	}
	else
		header ("Location: login.php?login=fail");
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
				<li id="current"><a href="login.php">Login</a></li>
				<li><a href="create.php">Create new Account</a></li>
				<?PHP if ($_SESSION["admin"] === 1)
					echo "<li><a href=\"admin.php\">Admin</a></li>";
				?>
			</ul>
		</div>
		<div id="body">
			<div id="form">
				<form  method="POST" action="login.php" >
					<h1>Login</h1>
					<div id="login">
						<label for="username">Username</label>
						<input type="text" name="user" placeholder="Enter username..." required>
						<label for="password">Password<?PHP if ($_GET["login"] == "fail") { echo "<div id=\"wrongPw\">Wrong password</div>";}?></label>
						<input type="password" name="passwd" placeholder="Enter password..." required>
						<input id="button" type="submit" name="submit" value="OK">
					</div>
				</form>
			</div>
		</div>
	</body>
</html>

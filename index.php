<?PHP
session_start();
include "install.php";


$servername = "127.0.0.1";
$username = "root";
$password = "root42";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn)
	die("Connection failed: " . mysqli_connect_error());

mysqli_query($conn, "USE db_parashop");

?>
<html>
	<head>
		<link rel="stylesheet" href="index.css">
		<link rel="shortcut icon" href="resources/parachute.png">
		<title>Parashop</title>
	</head>
	<body>
		<div id="header">
			<a href="."><img id="logo" src="resources/logo.png"></a>
			<ul id="menu">
			<li><a href="cart_state.php">Shopping Cart <?PHP if (isset($_SESSION["cart"]["total"])) { echo "(".$_SESSION["cart"]["total"].")";}?></a></li>
				<?PHP 
				if ($_SESSION["loggued_on_user"])
				{
					echo '<li><a href="destroy_session.php">Disconnect</a></li>';
					echo "<li><a>Hi ".$_SESSION["loggued_on_user"]."</a></li>";
				}
				else
				{
					echo '<li><a href="login.php">Login</a></li>';
					echo '<li><a href="create.php">Create new Account</a></li>';
				}
				?>
				<?PHP if ($_SESSION["admin"] === 1)
					echo "<li><a href=\"admin.php\">Admin</a></li>";
				?>
			</ul>
		</div>
		<div id="categoryBar">
			<div id="catContainer">
				<a href="index.php">All</a>
<?PHP

$result = mysqli_query($conn, "SELECT * FROM categories");

if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result))
		echo "<a href=\"index.php?category=". $row["name"] ."\">". $row["name"] ."</a>";
}
?>
			</div>
		</div>
		<hr>
		<div id="container">
<?PHP

$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

	if (isset($_GET["category"])) {
		while ($row = mysqli_fetch_assoc($result))
			if (in_array($_GET["category"], explode(",", $row["category"]))) {
				echo "<div class=\"item\">". $row["name"] ."<img class=\"picture\" src=\"resources/". $row["image"] ."\"><div class=\"desc\">". $row["description"] ."</div><div class=\"buy\">". $row["price"] ."&euro;<a href=\"add.php?id=". $row["id"] ."\">ADD TO CART</a></div></div>";
			}
	} else {
		while ($row = mysqli_fetch_assoc($result))
			echo "<div class=\"item\">". $row["name"] ."<img class=\"picture\" src=\"resources/". $row["image"] ."\"><div class=\"desc\">". $row["description"] ."</div><div class=\"buy\">". $row["price"] ."&euro;<a href=\"cart_handling.php?action=add&id=". $row["id"] ."\">ADD TO CART</a></div></div>";
	}
}

print_r ($_SESSION["cart"]);
?>
		</div>
	</body>
</html>

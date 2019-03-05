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
		<div id= "body_cart">
			<H1> Shopping Cart </H1>
			<?PHP
			$count = count($_SESSION["cart"]["ID"]);
			$total = 0;
			$i = 0;
			while ($i < $count)
			{
				$price = (($_SESSION["cart"]["price"][$i]) * ($_SESSION["cart"]["qty"][$i]));
				//echo "<div class=\"item\">". $_SESSION["cart"]["name"][$i] ."<img class=\"picture\" src=\"resources/". $_SESSION["cart"]["img"][$i] ."\"><div class=\"buy\">".$price ."&euro;<a href=\"cart_handling.php?action=del&id=". $_SESSION["cart"]["ID"][$i] ."\">DEL ITEM</a></div></div>";
				echo "<div class=\"item\">". $_SESSION["cart"]["name"][$i] ."<img class=\"picture\" src=\"resources/". $_SESSION["cart"]["img"][$i] ."\"><div class=\"desc\">". $_SESSION["cart"]["qty"][$i] ."</div><div class=\"buy\">".$price ."&euro;<a href=\"cart_handling.php?action=del&id=". $_SESSION["cart"]["ID"][$i] ."\">DEL ITEM</a></div></div>";
				$total += $price;
				$price = 0;
				$i++;
			}
			echo "<hr>";
			echo "<H2>Total = $total"."&euro;</H2>";
			echo "<div class =\"buy\"><a href=\"payment.php\"> VALIDATE CART</a></div>";
			echo "<div class =\"buy\"><a href=\"cart_handling.php?action=empty\"> EMPTY CART</a></div>";
			?>
		</div>
		<hr>
	</body>
</html>

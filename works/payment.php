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
				function	empty_cart()
				{
					$_SESSION["cart"] = NULL;
					//create_cart();
				}
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
		</div>
		<div id="body_payment">
		<?PHP
		
		if (!$_SESSION["loggued_on_user"])
		{
			echo "<H1>You must be logged on to pay<H1>";
			echo "<div class=\"buy\"><a href=\"login.php\">Sign in</a></div>";
			echo "<hr>";
		}
		else
		{
			$count = count($_SESSION["cart"]["ID"]);
			if ($count < 1)
				exit ();
			$total = 0;
			$i = 0;
			while ($i < $count)
			{
				$price = (($_SESSION["cart"]["price"][$i]) * ($_SESSION["cart"]["qty"][$i]));
				$total += $price;
				$price = 0;
				$i++;
			}
			$i = 0;
			$res = mysqli_query($conn, "SELECT order_id FROM orders ORDER BY id DESC LIMIT 1");
			$val = mysqli_fetch_assoc($res);
			$new_id = $val["order_id"] + 1;
			while ($i < $count)
			{
				$sql = "INSERT INTO orders (order_id, buyer, item_id, quantity, price) VALUES (".$new_id.", '".$_SESSION["loggued_on_user"]."', ".$_SESSION["cart"]["ID"][$i].", ".$_SESSION["cart"]["qty"][$i].", ".$total.")";
				if (mysqli_query($conn, $sql) === FALSE)
					exit();
				$i++;
			}
			echo "<H1> ORDER VALIDATED</H1>";
			empty_cart();
	}
		echo"</body></html>";
		?>

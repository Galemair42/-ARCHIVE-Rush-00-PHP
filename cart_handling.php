<?PHP
include "install.php";

/* OPEN CONNECTION AND SESSION*/

$servername = "127.0.0.1";
$username = "root";
$password = "root42";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn)
	die("Connection failed: " . mysqli_connect_error());
mysqli_query($conn, "USE db_parashop");

/* CART HANDLING FUNCTIONS */

function	retrieve_item_datas($ID)
{
	global $conn;
	$sql = 'SELECT * FROM items WHERE id = "'.$ID.'"';
	$datas = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($datas);
	return ($row);
}

function	create_cart()
{
	if (!isset($_SESSION["cart"]))
	{
		$_SESSION["cart"] = array ();
		$_SESSION["cart"]["ID"] = array();
		$_SESSION["cart"]["name"] = array();
		$_SESSION["cart"]["qty"] = array();
		$_SESSION["cart"]["price"] = array();
		$_SESSION["cart"]["img"] = array();
		$_SESSION["cart"]["total"] = 0;
	}
	return (TRUE);
}

function	empty_cart()
{
	$_SESSION["cart"] = array();
}

function	completely_remove_item_from_cart($ID)
{
	$datas = retrieve_item_datas($ID);
	if ($index = array_search($ID, $_SESSION["cart"]["ID"]) === FALSE)
		exit();
	$to_suppr = array_keys($_SESSION["cart"]["ID"], $datas["id"]);
	$_SESSION["cart"]["total"] -= $_SESSION["cart"]["qty"][$to_suppr[0]];
	array_splice($_SESSION["cart"]["ID"],$to_suppr[0], 1);
	array_splice($_SESSION["cart"]["name"],$to_suppr[0], 1);
	array_splice($_SESSION["cart"]["qty"],$to_suppr[0], 1);
	array_splice($_SESSION["cart"]["price"],$to_suppr[0], 1);
	array_splice($_SESSION["cart"]["img"],$to_suppr[0], 1);
}
//
///* QUANTITY - 1 */
//
function	remove_item_from_cart($ID)
{
	$datas = retrieve_item_datas($ID);
	if ($index = array_search($ID, $_SESSION["cart"]["ID"]) === FALSE)
		exit();	
	if ($_SESSION["cart"]["qty"][$index] == 1);
	{
		completely_revome_item_from_cart($ID);
		exit ();
	}
	$_SESSION["cart"]["qty"][$index]--;
}

function	add_item_to_cart($ID)
{
	create_cart();
	
	$datas = retrieve_item_datas($ID);
	
	if ($index = array_search($ID, $_SESSION["cart"]["ID"]) === FALSE)
	{
		array_push($_SESSION["cart"]["ID"], $datas["id"]);
		array_push($_SESSION["cart"]["name"], $datas["name"]);
		array_push($_SESSION["cart"]["qty"], 1);
		array_push($_SESSION["cart"]["price"], $datas["price"]);
		array_push($_SESSION["cart"]["img"], $datas["image"]);
		$_SESSION["cart"]["total"]++;
	}
	else
	{
		$_SESSION["cart"]["qty"][$index]++;
		$_SESSION["cart"]["total"]++;
	}
}

if ($_GET["action"] === "add")
{
	add_item_to_cart($_GET["id"]);
	header("Location: index.php");
	exit ();
}
switch ($_GET["action"])
{
	case ("less"):
		remove_item_from_cart($_GET["id"]);
	break;
	case ("del"):
		completely_remove_item_from_cart($_GET["id"]);
	break;
	case ("empty"):
		empty_cart();
	break;
}
//header("Location:.");
header("Location:cart_state.php");
?>

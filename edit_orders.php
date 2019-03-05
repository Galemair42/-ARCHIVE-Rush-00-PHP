<?PHP

include "install.php";

session_start();

//if ($_SESSION["admin"] != 1)
//	header("Location: .");

$servername = "127.0.0.1";
$username = "root";
$password = "root42";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn)
	die("Connection failed: " . mysqli_connect_error());

mysqli_query($conn, "USE db_parashop");

if (isset($_GET["del"])) {
	mysqli_query($conn, "DELETE FROM orders WHERE id=". $_GET["del"]);
} elseif (isset($_GET["delete"])) {
	mysqli_query($conn, "DELETE FROM orders WHERE order_id=". $_GET["delete"]);
} else {
		
	$sql = "SELECT * FROM orders";
	$result = mysqli_query($conn, $sql);
	
	if (($count = mysqli_num_rows($result)) > 0) {
		
		$i = 0;
		while ($row = mysqli_fetch_assoc($result)) {
	
			if ($i != 0 && $row["order_id"] != $orderID) {

				echo "<div for=\"price\">Price (". $row["price"] .")</div>
					<input type=\"number\" name=\"price\" step=\"0.01\" min=\"0\" max=\"100000\">
					<button class=\"submitBtn\" type=\"submit\" name=\"submit\" value=\"user". $row["id"] ."\">Submit</button>
					<a href=\"admin.php?db=orders&action=edit&delete=". $row["order_id"] ."\">Delete Order</a>
					</div>
				</form>
			</div><br>";
			}
			if ($row["order_id"] != $orderID) {

				echo "<div class=\"userFormContainer\">
						<form action=\"admin.php\" method=\"post\">
							<div class=\"userForm\">
								<div for=\"id\">Order ID (". $row["order_id"] .")</div>
								<input type=\"number\" name=\"order_id\" step=\"1\" min=\"1\" max=\"999999\">
								<div for=\"user\">Buyer</div>
								<input type=\"text\" name=\"buyer\" value=\"". $row["buyer"] ."\">
								<div for=\"item_id\">Item ID (". $row["item_id"] .")</div>
								<input type=\"number\" name=\"item_id\" step=\"1\" min=\"1\" max=\"999999\">
								<div for=\"quantity\">Quantity (". $row["quantity"] .")</div>
								<input type=\"number\" name=\"quantity\" step=\"1\" min=\"0\" max=\"100\">
								<a href=\"admin.php?db=orders&action=edit&del=". $row["id"] ."\">Delete Item</a>";
			} else {
				echo "<div for=\"item_id\">Item ID (". $row["item_id"] .")</div>
					<input type=\"number\" name=\"item_id\" step=\"1\" min=\"1\" max=\"999999\">
					<div for=\"quantity\">Quantity (". $row["quantity"] .")</div>
					<input type=\"number\" name=\"quantity\" step=\"1\" min=\"0\" max=\"100\">
					<a href=\"admin.php?db=orders&action=edit&del=". $row["id"] ."\">Delete Item</a>";
			}
			$orderID = $row["order_id"];
			$i++;
			if ($i == $count)
				echo "<div for=\"price\">Price (". $row["price"] .")</div>
					<input type=\"number\" name=\"price\" step=\"0.01\" min=\"0\" max=\"100000\">
					<button class=\"submitBtn\" type=\"submit\" name=\"submit\" value=\"user". $row["id"] ."\">Submit</button>
					<a href=\"admin.php?db=orders&action=edit&delete=". $row["order_id"] ."\">Delete Order</a>
					</div>
				</form>
			</div><br>";
		}
	}
}

mysqli_close($conn);

?>

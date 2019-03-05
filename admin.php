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

if (isset($_POST["submit"])) {
	
	if ($_POST["submit"] == "itemNew") {

		$sql = "INSERT IGNORE INTO items (category, name, description, price, image) VALUES (?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "sssds", $_POST["category"], $_POST["name"], $_POST["desc"], $_POST["price"], $_POST["image"]);
		mysqli_stmt_execute($stmt);
	} elseif ($_POST["submit"] == "orderNew") {

		$sql = "INSERT IGNORE INTO orders (order_id, buyer, item_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "isiid", $_POST["order_id"], $_POST["buyer"], $_POST["item_id"], $_POST["quantity"], $_POST["price"]);
		mysqli_stmt_execute($stmt);
	} elseif ($_POST["submit"] == "userNew") {
		
		$sql = "INSERT IGNORE INTO users (username, passwd, admin) VALUES (?, ?, ?)";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "ssi", $_POST["user"], hash("Whirlpool", $_POST["passwd"]), $_POST["admin"]);
		mysqli_stmt_execute($stmt);
	} elseif ($_POST["submit"] == "catNew") {
	
		$sql = "INSERT IGNORE INTO categories (name) VALUES (?)";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "s", $_POST["name"]);
		mysqli_stmt_execute($stmt);
	} else {

		var_dump($_POST);
		$id = filter_var($_POST["submit"], FILTER_SANITIZE_NUMBER_INT);
		if (strncmp($_POST["submit"], "user", 4) === 0) {
		
			$admin = (isset($_POST["admin"]) ? $_POST["admin"] : 0);
			$sql = "UPDATE users SET username = ?, passwd = ?, admin = ? WHERE id = ?";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, "ssii", $_POST["user"], hash("Whirlpool", $_POST["passwd"]), $admin, $id);
			mysqli_stmt_execute($stmt);
		} elseif (strncmp($_POST["submit"], "item", 4) === 0) {
	
			$sql = "UPDATE items SET category = ?, name = ?, description = ?, price = ?, image = ? WHERE id = ?";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, "sssdsi", $_POST["category"], $_POST["name"], $_POST["desc"], $_POST["price"], $_POST["image"], $id);
			mysqli_stmt_execute($stmt);
		} elseif (strncmp($_POST["submit"], "cat", 3)) {
		
			$sql = "UPDATE categories SET name = ? WHERE id = ?";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, "sd", $_POST["name"], $id);
			mysqli_stmt_execute($stmt);
		} elseif (strncmp($_POST["submit"], "order", 3)) {
		
			$sql = "UPDATE orders SET order_id = ?, buyer = ?, item_id = ?, quantity = ?, price = ? WHERE id = ?";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, "sd", $_POST["order_id"], $_POST["buyer"], $_POST["item_id"], $_POST["quantity"], $_POST["price"], $id);
			mysqli_stmt_execute($stmt);
		}
	}
}

?>
<html>
	<head>
		<link rel="stylesheet" href="index.css">
		<link rel="stylesheet" href="login.css">
		<link rel="stylesheet" href="admin.css">
		<link rel="shortcut icon" href="resources/parachute.png">
		<title>Parashop</title>
	</head>
	<body>
		<div id="header">
			<a href="."><img id="logo" src="resources/logo.png"></a>
			<ul id="menu">
				<li><a href="">Shopping Cart</a></li>
				<li><a href="login.php">Login</a></li>
				<li><a href="create.php">Create new Account</a></li>
				<li id="current"><a href="admin.php">Admin</a></li>
			</ul>
		</div>
		<div id="body">
			<div id="dbBar">
				<div class="dropdown">
				<button class=<?PHP if ($_GET["db"]=="users") {echo "\"dropBtnCurrent\"";} else {echo "\"dropBtn\"";}?>>Users</button>
					<div class="dropdownContent">
						<a href="admin.php?db=users&action=edit">Edit</a>
						<a href="admin.php?db=users&action=new">New</a>
					</div>
				</div>
				<div class="dropdown">
				<button class=<?PHP if ($_GET["db"]=="items") {echo "\"dropBtnCurrent\"";} else {echo "\"dropBtn\"";}?>>Items</button>
					<div class="dropdownContent">
						<a href="admin.php?db=items&action=edit">Edit</a>
						<a href="admin.php?db=items&action=new">New</a>
					</div>
				</div>
				<div class="dropdown">
				<button class=<?PHP if ($_GET["db"]=="cat") {echo "\"dropBtnCurrent\"";} else {echo "\"dropBtn\"";}?>>Categories</button>
					<div class="dropdownContent">
						<a href="admin.php?db=cat&action=edit">Edit</a>
						<a href="admin.php?db=cat&action=new">New</a>
					</div>
				</div>
				<div class="dropdown">
				<button class=<?PHP if ($_GET["db"]=="orders") {echo "\"dropBtnCurrent\"";} else {echo "\"dropBtn\"";}?>>Orders</button>
					<div class="dropdownContent">
						<a href="admin.php?db=orders&action=edit">Edit</a>
						<a href="admin.php?db=orders&action=new">New</a>
					</div>
				</div>
			</div>
<?PHP if ($_GET["db"] == "users") { if ($_GET["action"] == "edit") { include "edit_users.php"; } elseif ($_GET["action"] == "new") { ?>
				<form class="userFormContainer" action="admin.php" method="post">
					<div class="userFormNew">
						<div for="user">Username</div>
						<input type="text" name="user" placeholder="Enter username..." required>
						<div for="passwd">Password</div>
						<input type="password" name="passwd" placeholder="Enter password..." required>
						<div class="adminPerm"><input type="checkbox" name="admin" value="1">Admin Priviledges</div>
						<button class="submitBtn" type="submit" name="submit" value="userNew">Submit</button>
					</div>
				</form>
<?PHP }} elseif ($_GET["db"] == "items") { if ($_GET["action"] == "edit") { include "edit_items.php"; } elseif ($_GET["action"] == "new") { ?>
				<form class="userFormContainer" action="admin.php" method="post">
					<div class="userForm">
						<div for="name">Name</div>
						<input type="text" name="name" maxlength="100" placeholder="Item name..." required>
						<div for="category">Category</div>
						<input type="text" name="category" maxlength="255" placeholder="Category..." required>
						<div for=\"desc\">Description</div>
						<textarea name="desc" maxlength="3000"></textarea>
						<div for="price">Price</div>
						<input type="number" name="price" step="0.01" min="0" max="100000">
						<div for="image">Image</div>
						<input type="text" name="image" placeholder="image.jpg..." required>
						<button class="submitBtn" type="submit" name="submit" value="itemNew">Submit</button>
					</div>
				</form>
<?PHP }} elseif ($_GET["db"] == "cat") { if ($_GET["action"] == "edit") { include "edit_categories.php"; } elseif ($_GET["action"] == "new") { ?>
				<form class="userFormContainer" action="admin.php" method="post">
					<div class="userForm">
						<div for="name">Category Name</div>
						<input type="text" name="name" maxlength="25" placeholder="Category name..." required>
						<button class="submitBtn" type="submit" name="submit" value="catNew">Submit</button>
					</div>
				</form>
<?PHP }} elseif ($_GET["db"] == "orders") { if ($_GET["action"] == "edit") { include "edit_orders.php"; } elseif ($_GET["action"] == "new") { ?>
				<form class="userFormContainer" action="admin.php" method="post">
					<div class="userFormNew">
						<div for="id">Order ID</div>
						<input type="number" name="order_id" step="1" min="1" max="999999" placeholder="1..." required>
						<div for="user">Buyer</div>
						<input type="text" name="buyer" placeholder="Buyer username..." required>
						<div for="item_id">Item ID</div>
						<input type="number" name="item_id" step="1" min="1" max="999999" placeholder="1..." required>
						<div for="quantity">Quantity</div>
						<input type="number" name="quantity" step="1" min="1" max="100" placeholder="1..." required>
						<div for="price">Price</div>
						<input type="number" name="price" step="0.01" min="0" max="100000" placeholder="0.00..." required>
						<button class="submitBtn" type="submit" name="submit" value="orderNew">Submit</button>
					</div>
				</form>
<?PHP }} ?>
			</div>
		</div>
	</body>
</html>

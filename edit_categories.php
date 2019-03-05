<?PHP

include "install.php";

//if ($_SESSION["admin"] != 1)
//	header("Location: .");

$servername = "127.0.0.1";
$username = "root";
$password = "root42";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn)
	die("Connection failed: " . mysqli_connect_error());

mysqli_query($conn, "USE db_parashop");

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);

if (isset($_GET["del"])) {
	mysqli_query($conn, "DELETE FROM categories WHERE id=". $_GET["del"]);
} else {

	if (mysqli_num_rows($result) > 0) {
	
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<div class=\"userFormContainer\">
					<form action=\"edit_categories.php\" method=\"post\">
						<div class=\"userForm\">
							<div for=\"name\">Name</div>
							<input type=\"text\" name=\"name\" maxlength=\"25\" value=\"". $row["name"] ."\">
							<button class=\"submitBtn\" type=\"submit\" value=\"cat". $row["id"] ."\">Submit</button>
							<a href=\"admin.php?db=cat&action=edit&del=". $row["id"] ."\">Delete Category</a>
						</div>
					</form>
				</div><br>";
		}
	}
}

mysqli_close($conn);

?>

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

$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

if (isset($_GET["del"]))
{
	mysqli_query($conn, "DELETE FROM items WHERE id=". $_GET["del"]);
} else {
	
	if (mysqli_num_rows($result) > 0) {
	
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<div class=\"userFormContainer\">
					<form action=\"admin.php\" method=\"post\">
						<div class=\"userForm\">
							<div for=\"name\">Name</div>
							<input type=\"text\" name=\"name\" maxlength=\"100\" value=\"". $row["name"] ."\">
							<div for=\"category\">Category</div>
							<input type=\"text\" name=\"category\" maxlength=\"255\" value=\"". $row["category"] ."\">
							<div for=\"desc\">Description</div>
							<textarea name=\"desc\" maxlength=\"3000\">". $row["description"] ."</textarea>
							<div for=\"price\">Price (". $row["price"] .")</div>
							<input type=\"number\" name=\"price\" step=\"0.01\" min=\"0\" max=\"100000\">
							<div for=\"image\">Image</div>
							<input type=\"text\" name=\"image\" value=\"". $row["image"] ."\">
							<button class=\"submitBtn\" type=\"submit\" name=\"submit\" value=\"item". $row["id"] ."\">Submit</button>
							<a href=\"admin.php?db=items&action=edit&del=". $row["id"] ."\">Delete Item</a>
						</div>
					</form>
				</div><br>";
		}
	}
}
mysqli_close($conn);

?>

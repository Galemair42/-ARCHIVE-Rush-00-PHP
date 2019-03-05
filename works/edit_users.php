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

if (isset($_GET["del"]) && $_GET["del"] != 1) {
	mysqli_query($conn, "DELETE FROM users WHERE id=". $_GET["del"]);
} else {

	$sql = "SELECT * FROM users";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
	
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<div class=\"userFormContainer\">
					<form action=\"admin.php\" method=\"post\">
						<div class=\"userFormNew\">
							<div for=\"user\">Username</div>
							<input type=\"text\" name=\"user\" value=\"". $row["username"] ."\" maxlength=\"50\">
							<div for=\"passwd\">Password</div>
							<input type=\"text\" name=\"passwd\">
							<div class=\"adminPerm\"><input type=\"checkbox\" name=\"admin\" value=\"1\">Admin Priviledges</div>
							<button class=\"submitBtn\" type=\"submit\" name=\"submit\" value=\"user". $row["id"] ."\">Submit</button>
							<a href=\"admin.php?db=users&action=edit&del=". $row["id"] ."\">Delete User</a>
						</div>
					</form>
				</div><br>";
		}
	}
}

mysqli_close($conn);

?>

<?PHP
session_start();
include "index.php";
$tmp = $_SESSION["cart"];
session_destroy();
//session_start();
//$_SESSION["cart"] = $tmp;
?>

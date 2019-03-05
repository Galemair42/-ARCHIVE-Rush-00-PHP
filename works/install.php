<?PHP

session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "root42";



$conn = mysqli_connect($servername, $username, $password);

if (!$conn)
	die("Connection failed: " . mysqli_connect_error());

$check = mysqli_query($conn, "SHOW DATABASES LIKE 'db_parashop'");
if (mysqli_num_rows($check) === 0) {
	
	$sql = "CREATE DATABASE IF NOT EXISTS db_parashop";
	
	if (!mysqli_query($conn, $sql))
		echo "Error creating database: " . mysqli_error($conn);
	
	if (!mysqli_query($conn, "USE db_parashop"))
		echo "Error connecting to database: " . mysqli_error($conn);
	
	/* SHOP ELEMENTS TABLE CREATION */
	
	$sql = "CREATE TABLE IF NOT EXISTS items (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	category VARCHAR(255) NOT NULL,
	name VARCHAR(255) UNIQUE NOT NULL,
	description TEXT(3000) NOT NULL,
	price FLOAT(6,2) UNSIGNED NOT NULL,
	image VARCHAR(255) NOT NULL
	)";
	
	if (!mysqli_query($conn, $sql))
		echo "Error creating table: " . mysqli_error($conn);
	
	/* USERNAME/PASSWD TABLE CREATION WITH MAXIMUM LENGTH OF 50/50 CHAR */
	
	$sql = "CREATE TABLE IF NOT EXISTS users (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) UNIQUE NOT NULL,
	passwd VARCHAR(512) NOT NULL,
	admin TINYINT(1) DEFAULT 0 NOT NULL
	)";
	
	if (!mysqli_query($conn, $sql))
		echo "Error creating table: " . mysqli_error($conn);
	
	$passwd = hash("Whirlpool", "admin");
	
	if (!mysqli_query($conn, "INSERT IGNORE INTO users VALUES (1, 'admin', '".$passwd."', 1)"))
		echo "Error: " . mysqli_error($conn);
	
	/* ORDERS HISTORY TABLE CREATION */
	
	$sql = "CREATE TABLE IF NOT EXISTS orders (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	order_id INT(6) UNSIGNED NOT NULL,
	buyer VARCHAR(50) NOT NULL,
	item_id INT(6) UNSIGNED NOT NULL,
	quantity INT(6) NOT NULL,
	price FLOAT (6,2) UNSIGNED NOT NULL
	)";
	
	if (!mysqli_query($conn, $sql))
		echo "Error creating table: " . mysqli_error($conn);
	
	
	/* --! CREATE ADMIN USER !-- */
	
	$categories = array (
		"Canopies", "Canopies", "Canopies", "Canopies", "Canopies", "Canopies,Jumpsuits,Helmets",
		"Containers", "Containers", "Containers", "Containers", "Containers", "Containers",
		"Helmets", "Helmets", "Helmets", "Helmets", "Helmets", "Helmets",
		"Jumpsuits", "Jumpsuits", "Jumpsuits", "Jumpsuits", "Jumpsuits", "Jumpsuits",
		"Accessories", "Accessories", "Accessories", "Accessories", "Accessories", "Accessories"
	);
	
	$names = array (
		"Aerodyne Pilot", "Aerodyne Zulu", "Icarus Crossfire", "Icarus Safire", "Performance Designs Katana", "Quebec Flag",
		"Mirage Systems G4", "Nexgen Icon Pro", "Vodoo Curve", "Velocity Sports Infinity", "Javelin Odyssey", "Vector 3",
		"Cookie G3", "Bonehead Aero", "Bonehead Rev2", "Phantom XV", "Cookie Fuel", "KISS",
		"Tonfly Basic B1", "Parasport F1", "Vertical Suits RW", "Vertical Suits Fusion", "PittZ Freefly", "Tonfly Uno Race",
		"Viso II+", "ARES II", "Atlas Visual", "Alti-2 Altimaster Galaxy", "Parasport Aeronaut", "ALTITRACK"
	);
	
	$descriptions = array (
		"Helps you not hit the ground too fast.", "Helps you not hit the ground too fast.", "Helps you not hit the ground too fast.", "Helps you not hit the ground too fast.", "Helps you not hit the ground too fast.", "Straight out of the most ancient myths, this legendary item gives you immesurable patriotic power which lets not hit the ground. Can also be wrapped around your body or head to protect them against everything... except yourself.",
		"Holds the thing that helps you not hit the ground too fast.", "Holds the thing that helps you not hit the ground too fast.", "Holds the thing that helps you not hit the ground too fast.", "Holds the thing that helps you not hit the ground too fast.", "Holds the thing that helps you not hit the ground too fast.", "Holds the thing that helps you not hit the ground too fast.",
		"Protects your face when you hit the ground.", "Protects your face when you hit the ground.", "Protects your face when you hit the ground.", "Protects your face when you hit the ground.", "Protects your face when you hit the ground.", "Protects your face when you hit the ground.",
		"Contains the pieces of your body after you hit the ground.", "Contains the pieces of your body after you hit the ground.", "Contains the pieces of your body after you hit the ground.", "Contains the pieces of your body after you hit the ground.", "Contains the pieces of your body after you hit the ground.", "Contains the pieces of your body after you hit the ground.",
	   "Helps you pass the time while you get closer to hitting the ground.", "Helps you pass the time while you get closer to hitting the ground.", "Helps you pass the time while you get closer to hitting the ground.", "Helps you pass the time while you get closer to hitting the ground.", "Helps you pass the time while you get closer to hitting the ground.", "Helps you pass the time while you get closer to hitting the ground."
	);
	
	$prices = array (
		2000.00, 2250.00, 2300.00, 2075.00, 2435.00, 3.50,
		2199.00, 2450.00, 2699.00, 1995.00, 2495.00, 1885.00,
		380.00, 415.00, 425.00, 300.00, 249.00, 430.00,
		285.00, 320.00, 345.00, 595.00, 220.00, 630.00,
		299.00, 399.00, 399.00, 169.00, 349.00, 200.00
	);
	
	$images = array (
		"aerodyne_pilot.jpg", "aerodyne_zulu.jpg", "icarus_crossfire.jpg", "icarus_safire.jpg", "pd_katana.jpg", "quebec.jpg",
		"mirage_g4.jpg", "nexgen_icon.jpg", "vodoo_curve.jpg", "velocity_infinity.jpg", "javelin_odyssey.jpg", "vector_3.jpg",
		"cookie_g3.jpg", "bonehead_aero.jpg", "bonehead_rev2.jpg", "phantom_xv.jpg", "cookie_fuel.jpg", "KISS.jpg",
		"tonfly_basic_b1.jpg", "parasport_f1.jpg", "vertical_suit_rw.jpg", "vertical_suit_fusion.jpg", "pittz_freefly.jpg", "tonfly_uno_race.jpg",
		"visoii_plus.jpg", "ares_ii.jpg", "atlas_visual.jpg", "alti2_altimaster.jpg", "parasport_aeronaut.jpg", "altitrack.jpg"
	);
	
	$sql = "INSERT IGNORE INTO items (category, name, description, price, image) VALUES (?, ?, ?, ?, ?)";
	$stmt = mysqli_prepare($conn, $sql);
	
	foreach ($categories as $key => $category) {
		
		mysqli_stmt_bind_param($stmt, "sssds", $category, $names[$key], $descriptions[$key], $prices[$key], $images[$key]);
		mysqli_stmt_execute($stmt);
	}
	
	mysqli_stmt_close($stmt);
	
	$sql = "CREATE TABLE IF NOT EXISTS categories (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(25) UNIQUE NOT NULL
	)";
	
	if (!mysqli_query($conn, $sql))
		echo "Error creating table: " . mysqli_error($conn);
	
	mysqli_query($conn, "INSERT INTO categories (name) VALUES ('Canopies')");
	mysqli_query($conn, "INSERT INTO categories (name) VALUES ('Containers')");
	mysqli_query($conn, "INSERT INTO categories (name) VALUES ('Helmets')");
	mysqli_query($conn, "INSERT INTO categories (name) VALUES ('Jumpsuits')");
	mysqli_query($conn, "INSERT INTO categories (name) VALUES ('Accessories')");
}

mysqli_close($conn);

?>

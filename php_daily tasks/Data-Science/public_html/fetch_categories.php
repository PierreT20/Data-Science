<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd';
$dbname = "id21804836_projdata";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = $conn->query("SELECT id, name FROM categories");
$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);

$conn->close();
?>

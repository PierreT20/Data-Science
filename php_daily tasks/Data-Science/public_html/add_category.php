<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd'; 
$dbname = "id21804836_projdata";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'addCategory') {
    $category_name = $conn->real_escape_string($_POST['category_name']);

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
}

$conn->close();
?>

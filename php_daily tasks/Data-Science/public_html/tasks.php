<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd'; 
$dbname = "id21804836_projdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? '';
if ($action == 'addTask') {
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $category_id = $_POST['category_id'] === '' ? NULL : $_POST['category_id']; 
    $priority = $_POST['priority'] === '' ? NULL : $_POST['priority'];
    $status = $_POST['status'] ?? 'active'; 

    $stmt = $conn->prepare("INSERT INTO tasks (description, due_date, category_id, priority, status) VALUES (?, ?, ?, ?, ?)");
    if ($category_id === NULL && $priority === NULL) {
        $stmt->bind_param("sssss", $description, $due_date, $category_id, $priority, $status);
    } else {
        $stmt->bind_param("sssis", $description, $due_date, $category_id, $priority, $status);
    }
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php"); 
?>

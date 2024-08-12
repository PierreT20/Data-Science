<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd';
$dbname = "id21804836_projdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = [];
$stmt = $conn->prepare("SELECT id, name FROM categories");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $category_id = $_POST['category_id'];
    $priority = $_POST['priority'];

    $stmt = $conn->prepare("UPDATE tasks SET description=?, due_date=?, category_id=?, priority=? WHERE task_id=?");
    $stmt->bind_param("ssiii", $description, $due_date, $category_id, $priority, $task_id);
    $stmt->execute();
    $stmt->close();

    header("Location: display_tasks.php");
    exit;
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $conn->prepare("SELECT * FROM tasks WHERE task_id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    $stmt->close();

    // Aesthetic color palette
    $colors = ['#F0EAD6', '#FFF0F5', '#FFFACD', '#FFEFDB', '#E6E6FA', '#E0FFFF', '#FFFFF0', '#F5F5F5'];
    $backgroundColor = $colors[array_rand($colors)];

    // Function to generate darker color for text
    function getDarkerColor($color) {
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));

        // Darken color by 20%
        $r = round($r * 0.8);
        $g = round($g * 0.8);
        $b = round($b * 0.8);

        $newColor = sprintf("#%02x%02x%02x", $r, $g, $b);
        return $newColor;
    }

    $textColor = getDarkerColor($backgroundColor);
    $inputBorderColor = getDarkerColor($backgroundColor);
    $buttonColor = $colors[array_rand($colors)];
    $buttonHoverColor = getDarkerColor($buttonColor);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: <?php echo $backgroundColor; ?>; margin: 20px; color: <?php echo $textColor; ?>; }
        form { background-color: <?php echo $backgroundColor; ?>; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: <?php echo $textColor; ?>; }
        input[type="text"], input[type="date"], select { width: 100%; padding: 10px; border: 2px solid <?php echo $inputBorderColor; ?>; border-radius: 4px; box-sizing: border-box; color: <?php echo $textColor; ?>; }
        button { background-color: <?php echo $buttonColor; ?>; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: <?php echo $buttonHoverColor; ?>; }
    </style>
</head>
<body>
    <h1>Edit Task</h1>
    <form action="edit_task.php" method="post">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        <div>
            <label>Description:</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($task['description']); ?>" required>
        </div>
        <div>
            <label>Due Date:</label>
            <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
        </div>
        <div>
            <label>Category:</label>
            <select name="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $task['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Priority:</label>
            <select name="priority">
                <option value="1" <?php echo $task['priority'] == 1 ? 'selected' : ''; ?>>1 (Highest)</option>
                <option value="2" <?php echo $task['priority'] == 2 ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo $task['priority'] == 3 ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo $task['priority'] == 4 ? 'selected' : ''; ?>>4 (Lowest)</option>
            </select>
        </div>
        <button type="submit" name="update_task">Update Task</button>
    </form>
</body>
</html>
<?php
} else {
    echo "No task ID provided for editing.";
}
$conn->close();
?>

<?php
$serverName = "localhost";
$dbUsername = "id21804836_root";
$dbPassword = 'P@ssw0rd';
$databaseName = "id21804836_projdata";

$databaseConnection = new mysqli($serverName, $dbUsername, $dbPassword, $databaseName);
if ($databaseConnection->connect_error) {
    die("Unable to connect: " . $databaseConnection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $catId = $_POST['category_id'];
    $catName = $_POST['category_name'];

    $updateStmt = $databaseConnection->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $updateStmt->bind_param("si", $catName, $catId);
    if ($updateStmt->execute()) {
        echo "<p class='message' id='successMsg'>Category updated successfully.</p>";
    } else {
        echo "<p class='message' id='errorMsg'>Update failed: " . $updateStmt->error . "</p>";
    }
    $updateStmt->close();
}

$categoryData = $databaseConnection->query("SELECT id, name FROM categories");
$databaseConnection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        h1 {
            text-shadow: 1px 1px 0px #ffffff;
            font-weight: bold;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 12px 18px;
            margin-bottom: 10px;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
        }
        a, .home-button {
            text-decoration: none;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
            font-weight: bold;
        }
        .message {
            padding: 12px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Manage Categories</h1>
    <button class="home-button" onclick="window.location.href='index.php';">Return to Home</button>
    <?php if ($categoryData->num_rows > 0): ?>
        <ul>
            <?php while($category = $categoryData->fetch_assoc()): ?>
                <li id="category_<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?> 
                <a href='edit_category.php?id=<?= $category['id'] ?>' class='edit-link'>Edit</a></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class='message' id='noCategoriesMsg'>No categories to display.</p>
    <?php endif; ?>
    <script>
        document.querySelectorAll('li').forEach(el => {
            const randomColor = '#' + (Math.floor(Math.random()*16777215) & 0xFFFFFF).toString(16).padStart(6, '0');
            el.style.backgroundColor = `#${randomColor}`;
            el.style.color = '#000';
            el.style.boxShadow = "0 5px 15px rgba(0,0,0,0.1)";
        });

        document.querySelectorAll('.edit-link, .home-button').forEach(el => {
            const randomColor = '#' + (Math.floor(Math.random()*16777215) & 0xFFFFFF).toString(16).padStart(6, '0');
            el.style.backgroundColor = `#${randomColor}`;
            el.style.color = '#000';
            el.onmouseover = () => el.style.boxShadow = "0 4px 8px rgba(0,0,0,0.18)";
            el.onmouseout = () => el.style.boxShadow = "0 3px 6px rgba(0,0,0,0.12)";
        });

        document.querySelectorAll('.message').forEach(el => {
            const isSuccess = el.id === 'successMsg';
            el.style.backgroundColor = isSuccess ? '#4caf50' : '#e53935';
        });

        const h1Style = document.querySelector('h1');
        h1Style.style.color = '#' + (Math.floor(Math.random()*16777215) & 0xFFFFFF).toString(16).padStart(6, '0');
    </script>
</body>
</html>
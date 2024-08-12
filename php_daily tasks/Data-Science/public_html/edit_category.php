<?php
$servername = "localhost";
$username = "id21804836_root";
$password = 'P@ssw0rd';
$dbname = "id21804836_projdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $category_name, $category_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating category: " . $stmt->error;
    }
}

$category_id = $_GET['id'] ?? 1; 
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
</head>
<body>
    <h1>Edit Category</h1>
    <form method="POST" action="">
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>">
        <button type="submit" name="submit">Update Category</button>
    </form>
    <?php if(isset($stmt->error) && $stmt->error): ?>
        <p class="error-message"><?= "Error updating category: " . htmlspecialchars($stmt->error); ?></p>
    <?php endif; ?>
    <script>
        const setRandomStyles = () => {
            const getRandomColor = (dark = false) => {
                let letters = dark ? '0123456789' : '89ABCDEF'; 
                let color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * letters.length)];
                }
                return color;
            };

            document.body.style.backgroundColor = getRandomColor();
            document.body.style.color = '#101010';
            document.body.style.fontFamily = 'Arial, sans-serif';
            document.body.style.padding = '20px';
            document.body.style.margin = '0';

            const h1 = document.querySelector('h1');
            h1.style.color = getRandomColor();
            h1.style.textAlign = 'center';

            const form = document.querySelector('form');
            form.style.backgroundColor = '#FFFFFF';
            form.style.padding = '20px';
            form.style.borderRadius = '9px';
            form.style.boxShadow = '0 3px 7px rgba(0,0,0,0.1)';
            form.style.width = '301px';
            form.style.margin = '0 auto';

            const inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.style.width = '100%';
                input.style.padding = '11px';
                input.style.marginTop = '9px';
                input.style.borderRadius = '5px';
                input.style.border = '1px solid #ccc';
            });

            const button = document.querySelector('button');
            button.style.backgroundColor = getRandomColor(true); 
            button.style.color = 'white';
            button.style.border = 'none';
            button.style.cursor = 'pointer';
            button.style.fontSize = '17px';
            button.style.width = '101%';
            button.onmouseover = () => button.style.backgroundColor = '#454545'; 
            button.onmouseout = () => button.style.backgroundColor = getRandomColor(true); 
        };

        setRandomStyles();
    </script>
</body>
</html>
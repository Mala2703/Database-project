<?php
$conn = new mysqli("localhost", "root", "", "poll_system");

// Fetch latest poll
$poll = $conn->query("SELECT * FROM polls ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
$options = $conn->query("SELECT * FROM options WHERE poll_id = " . $poll['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $option_id = $_POST['option'];
    $conn->query("UPDATE options SET votes = votes + 1 WHERE id = $option_id");
    header("Location: results.php?poll_id=" . $poll['id']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Poll System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9966, #ff5e62);
            text-align: center;
            padding: 20px;
        }
        .poll-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            width: 450px;
            margin: auto;
            position: relative;
        }
        .poll-icon {
            font-size: 50px;
            color: #ff5e62;
        }
        .poll-question {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .option {
            display: block;
            padding: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .option:hover {
            transform: scale(1.05);
        }
        .vote-btn {
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            transition: 0.3s;
        }
        .vote-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="poll-container">
    <div class="poll-icon">🗳️</div>
    <h2 class="poll-question"><?php echo $poll['question']; ?></h2>
    <form method="POST">
        <?php while ($option = $options->fetch_assoc()): ?>
            <label class="option">
                <input type="radio" name="option" value="<?php echo $option['id']; ?>" required> 
                <?php echo $option['option_text']; ?>
            </label>
        <?php endwhile; ?>
        <br>
        <button type="submit" class="vote-btn">✅ Submit Vote</button>
    </form>
</div>

</body>
</html>

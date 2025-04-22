<<?php
$conn = new mysqli("localhost", "root", "", "poll_system");

$poll_id = $_GET['poll_id'];
$poll = $conn->query("SELECT * FROM polls WHERE id = $poll_id")->fetch_assoc();
$options = $conn->query("SELECT * FROM options WHERE poll_id = $poll_id");

$total_votes = $conn->query("SELECT SUM(votes) as total FROM options WHERE poll_id = $poll_id")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Poll Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            text-align: center;
            padding: 20px;
        }
        .result-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
            margin: auto;
        }
        .poll-icon {
            font-size: 50px;
            color: #2575fc;
        }
        .poll-question {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .result-list {
            list-style: none;
            padding: 0;
        }
        .result-list li {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .progress-container {
            width: 80px;
            height: 80px;
            position: relative;
            display: inline-block;
        }
        .circle-bg {
            stroke: #eee;
            stroke-width: 10;
        }
        .circle {
            stroke: #2575fc;
            stroke-width: 10;
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            transition: stroke-dashoffset 1s ease-in-out;
        }
        .progress-text {
            font-size: 16px;
            font-weight: bold;
            fill: #2575fc;
        }
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 15px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="result-container">
    <div class="poll-icon">📊</div>
    <h2 class="poll-question"><?php echo $poll['question']; ?></h2>
    <ul class="result-list">
        <?php while ($option = $options->fetch_assoc()): 
            $percentage = ($total_votes > 0) ? round(($option['votes'] / $total_votes) * 100) : 0;
            $offset = 251.2 - (251.2 * $percentage / 100);
        ?>
            <li>
                <span><?php echo $option['option_text']; ?></span>
                <div class="progress-container">
                    <svg width="80" height="80">
                        <circle cx="40" cy="40" r="40" fill="none" class="circle-bg" />
                        <circle cx="40" cy="40" r="40" fill="none" class="circle" 
                            style="stroke-dashoffset: <?php echo $offset; ?>;" />
                        <text x="50%" y="50%" text-anchor="middle" dy=".3em" class="progress-text">
                            <?php echo $percentage; ?>%
                        </text>
                    </svg>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
    <br>
    <a href="index.php" class="back-btn">🔙 Back to Poll</a>
</div>

</body>
</html>

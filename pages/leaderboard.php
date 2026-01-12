<?php include '../includes/header.php'; ?>

<?php
// Fetch top 5 scores for this specific quiz
$leader_sql = "SELECT users.full_name, results.score, results.total_questions, results.taken_at 
               FROM results 
               JOIN users ON results.user_id = users.id 
               WHERE results.quiz_id = $quiz_id 
               ORDER BY results.score DESC, results.taken_at ASC 
               LIMIT 5";
$leader_result = $conn->query($leader_sql);
?>

<div class="leaderboard-section" style="margin-top: 50px; background: #f8f9fa; padding: 20px; border-radius: 10px;">
    <h3>🏆 Quiz Leaderboard</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr style="border-bottom: 2px solid #ddd;">
            <th style="text-align: left; padding: 10px;">Rank</th>
            <th style="text-align: left; padding: 10px;">Name</th>
            <th style="text-align: right; padding: 10px;">Score</th>
        </tr>
        <?php 
        $rank = 1;
        while($row = $leader_result->fetch_assoc()): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><?php echo $rank++; ?></td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td style="padding: 10px; text-align: right; font-weight: bold;">
                    <?php echo $row['score']; ?>/<?php echo $row['total_questions']; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
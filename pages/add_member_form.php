<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Members</title>
</head>
<body>
    <h2>Add Members to Group</h2>
    <form action="add_member.php" method="post">
        <label for="group_id">Group ID:</label><br>
        <input type="text" id="group_id" name="group_id" required><br>
        <label for="user_ids">User IDs (comma-separated):</label><br>
        <textarea id="user_ids" name="user_ids" required></textarea><br>
        <input type="submit" value="Add Members">
    </form>
</body>
</html>

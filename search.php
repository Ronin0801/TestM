<?php
include 'connections.php';
include 'nav.php';

$searchTerm = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchTerm = trim($_POST['search']);

    // Use LIKE to match names with the search term
    $sql = "SELECT id, name, email, address, acc_type FROM test_tbl WHERE name LIKE ?";
    $stmt = $connections->prepare($sql);
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $likeTerm);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Users</title>
</head>
<body>
    <h2>Search Users by Name</h2>
    <form method="POST" action="search.php">
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter name" required>
        <input type="submit" value="Search">
    </form>

    <br>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <h3>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h3>
        <?php if ($results->num_rows > 0): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Account Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo $row['acc_type'] == 1 ? "Admin" : "User"; ?></td>
                            <td>
                                <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($row['name']); ?>?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php
$connections->close();
?>

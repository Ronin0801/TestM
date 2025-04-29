<?php
// update.php

include 'connections.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the existing data
    $sql = "SELECT * FROM test_tbl WHERE id = ?";
    $stmt = $connections->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_address = $_POST['address'];

    $update_sql = "UPDATE test_tbl SET name = ?, email = ?, address = ? WHERE id = ?";
    $update_stmt = $connections->prepare($update_sql);
    $update_stmt->bind_param("sssi", $new_name, $new_email, $new_address, $id);

    if ($update_stmt->execute()) {
        echo "<script>alert('User updated successfully.'); window.location.href='read.php';</script>";
    } else {
        echo "Error updating user: " . $update_stmt->error;
    }

    $update_stmt->close();
}

$connections->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>

    <form action="" method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

        <label>Address:</label><br>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br><br>

        <input type="submit" value="Update">
    </form>

    <p><a href="read.php">Back to User List</a></p>
</body>
</html>

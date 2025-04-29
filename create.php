<?php
// create.php

include 'connections.php';
include 'nav.php';

$passwordErr = $confirmPasswordErr = $emailErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $address  = $_POST['address'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Password match check
    if ($password !== $confirm) {
        $confirmPasswordErr = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_email = $connections->prepare("SELECT * FROM test_tbl WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email_result = $check_email->get_result();

        if ($check_email_result->num_rows > 0) {
            $emailErr = "Email is already registered!";
        } else {
            // Hash the password before saving it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO test_tbl (name, email, address, password) VALUES (?, ?, ?, ?)";
            $stmt = $connections->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $address, $hashed_password);

            if ($stmt->execute()) {
                echo "<script language='javascript'>alert('User created successfully.')</script>";
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
        $check_email->close();
        $connections->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Create User</h2>
    <form action="create.php" method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <span class="error"><?php echo $emailErr; ?></span><br><br>

        <label>Address:</label><br>
        <input type="text" name="address"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br>
        <span class="error"><?php echo $confirmPasswordErr; ?></span><br><br>

        <input type="submit" value="Create">
    </form>
</body>
</html>

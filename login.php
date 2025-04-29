<?php
session_start();
include 'connections.php';
include 'nav.php';

$email = $password = "";
$emailErr = $passwordErr = "";
$loginSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required!";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required!";
    } else {
        $password = $_POST["password"];
    }

    if ($email && $password) {
        $stmt = $connections->prepare("SELECT * FROM test_tbl WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // Set session if needed
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["acc_type"] = $user["acc_type"];

                // Redirect based on acc_type
                if ($user["acc_type"] == 1) {
                    echo "<script>window.location.href='admin/';</script>";
                } else {
                    echo "<script>window.location.href='user/';</script>";
                }
                $loginSuccess = true;
            } else {
                $passwordErr = "Password is incorrect!";
            }
        } else {
            $emailErr = "Email is not registered!";
        }

        $stmt->close();
    }

    $connections->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
        <span class="error"><?php echo $emailErr; ?></span><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br>
        <span class="error"><?php echo $passwordErr; ?></span><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>

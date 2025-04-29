<!-- nav.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="login.php">Login</a>
    <a href="create.php">Register</a>
    <a href="read.php">Accounts</a>
    <a href="search.php">Search</a>
</div>

</body>
</html>

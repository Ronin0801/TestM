<?php
// delete.php

include 'connections.php';

// Get the ID from the URL and make sure it's an integer
$id = intval($_GET['id']);

// Prepare the DELETE statement
$stmt = $connections->prepare("DELETE FROM test_tbl WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
            alert('User deleted successfully.');
            window.location.href = 'read.php';
          </script>";
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$connections->close();
?>

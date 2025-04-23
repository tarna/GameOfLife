<?php
require 'config.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$dbname = $config['dbname'];

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Could not connect to server\n";
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS USERS (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table USERS created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$users = $conn->query("SELECT * FROM USERS");

// if no users exist, create an example user
$amount = $users->num_rows;
if ($amount > 0) {
    echo "Users exist\n";
} else {
    echo "No users exist, creating example user\n";
    $sql = "INSERT INTO USERS (name, email, password) VALUES ('John Doe', 'john@gmail.com', 'password123')";
    if ($conn->query($sql) === TRUE) {
        echo "Example user created successfully\n";
    } else {
        echo "Error creating example user: " . $conn->error . "\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>GOL Admin</title>

    <script>
        function banUser(userId) {
            if (confirm("Are you sure you want to ban this user?")) {
                // Implement ban logic here
                alert("User " + userId + " has been banned.");
            }
        }

        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                // Implement delete logic here
                alert("User " + userId + " has been deleted.");
            }
        }

        function openEditModal(id, name, email) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</head>
<body>
    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Actions</th>
    </tr>
    <?php
        $users = $conn->query("SELECT * FROM USERS");
        while ($row = $users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>
                    <button onclick=\"deleteUser(" . $row['id'] . ")\">Delete</button>
                    <button onclick=\"openEditModal(" . $row['id'] . ", '" . $row['name'] . "', '" . $row['email'] . "')\">Edit</button>
                  </td>";
            echo "</tr>";
        }
        $conn->close();
    ?>
</table>

<div id="editModal" style="display:none;">
    <form id="editForm" method="POST" action="edit_user.php">
        <input type="hidden" name="id" id="editUserId">
        <label for="editName">Name:</label>
        <input type="text" name="name" id="editName" required>
        <label for="editEmail">Email:</label>
        <input type="email" name="email" id="editEmail" required>
        <button type="submit">Save</button>
        <button type="button" onclick="closeEditModal()">Cancel</button>
    </form>
</div>
</body>
</html>
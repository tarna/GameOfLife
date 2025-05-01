<?php
require 'database.php';

createAllTables();

$conn = connectToDatabase();

$user = getUser();
if ($user['role'] !== 'admin') {
    echo "You do not have permission to access this page.";
    exit;
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
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_user.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
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
    <h1>Admin Dashboard</h1>

    <h2>Users</h2>
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
        ?>
    </table>

    <h2>Sessions</h2>
    <table>
        <tr>
            <th>Session ID</th>
            <th>User ID</th>
            <th>Generations</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        <?php
            $sessions = $conn->query("SELECT * FROM SESSIONS");
            while ($row = $sessions->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['generations'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "</tr>";
            }
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
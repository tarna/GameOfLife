<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    $conn = connectToDatabase();

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM USERS WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set cookies and log in
            setcookie("name", $user['name'], time() + (86400 * 30), "/");
            setcookie("email", $user['email'], time() + (86400 * 30), "/");
            echo "Login successful!";

            header("Location: main.html");
        } else {
            echo "Incorrect password.";
        }
    } else {
        // User does not exist, create account
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO USERS (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            setcookie("name", $name, time() + (86400 * 30), "/");
            setcookie("email", $email, time() + (86400 * 30), "/");
            echo "Account created and logged in successfully!";

            header("Location: main.html");
        } else {
            echo "Error creating account.";
        }
    }

    $stmt->close();
    $conn->close();
}
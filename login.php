<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = ""; // Initialize an empty message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Database connection
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "weighbridge";

    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);

    // Check if the prepared statement is successful
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, user is authenticated
            echo "Login successful";
           
            // You can redirect to the main dashboard or another page
            header("Location: dashboard.php");
        } else {
            // Password is incorrect
            $message = "Invalid password";
        }
    } else {
        // Username not found
        $message = "Invalid username";
    }

    // Close the prepared statement and the database
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Include your external CSS file -->
    <title>Login Page</title>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <div class="logo-container">
                <img src="ktda.png" alt="Logo" width="150" height="150">
            </div>
            <h2>Rorok Tea Factory</h2>
            
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
            
            <!-- Display the message if it is not empty -->
            <?php if (!empty($message)) : ?>
                <p style="color: red;"><?php echo $message; ?></p>
            <?php endif; ?>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

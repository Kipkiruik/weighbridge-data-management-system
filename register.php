<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Include your external CSS file -->
    <title>Registration Page</title>
</head>
<body>
    <div class="container">
        <form action="register.php" method="post">
            <img src="ktda.png" width="150" height="150" >
            <h2>Rorok Tea Factory</h2>
            
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    

    // Validate inputs (add your validation logic here)

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match");</script>';
    } else {
        // Hash the password (use a strong hashing algorithm)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Database connection
        $servername = "localhost";  // Assuming your MySQL server is on the same machine
        $username_db = "root";      // Default username for XAMPP
        $password_db = "";          // Default password for XAMPP is often empty
        $dbname = "weighbridge";

        // Create connection
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the username already exists
        $check_username_query = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($check_username_query);

        if ($result->num_rows > 0) {
            echo '<script>alert("Username already exists. Please choose a different one.");</script>';
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("Registration successful");</script>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();
    }
}
?>


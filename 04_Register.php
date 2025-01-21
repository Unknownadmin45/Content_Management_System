<?php
    session_start();
    require_once '04_Database_Management.php';
             // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
                // Get the username, email, and password from the POST request
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
                // Validate the username, email, and password
        if (empty($username) || empty($email) || empty($password)) 
        {
            $_SESSION['flash_message'] = "Error: All fields are required.";
            $_SESSION['flash_message_type'] = 'danger'; 
            header("Location: 04_Register.php");
            exit;
        }
                // Validate the email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            $_SESSION['flash_message'] = "Error: Invalid email.";
            $_SESSION['flash_message_type'] = 'danger'; 
            header("Location: 04_Register.php");
            exit;
        }
                // Validate the password
        if (strlen($password) < 8) 
        {
            $_SESSION['flash_message'] = "Error: Password must be at least 8 characters.";
            $_SESSION['flash_message_type'] = 'danger'; 
            header("Location: 04_Register.php");
            exit;
        }
                // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) 
        {
            $_SESSION['flash_message'] = "Warning: Username or email already exists.";
            $_SESSION['flash_message_type'] = 'danger';
            $stmt->close();
            header("Location: 04_Register.php");
            exit;
        }
        $stmt->close();
                // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                // Prepare the statement
        $stmt = $conn->prepare($sql);
                // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $passwordHash);
                // Execute the query
        if ($stmt->execute()) 
        {
            $_SESSION['flash_message'] = "Registration successful!";
            $_SESSION['flash_message_type'] = 'success'; 
            header("Location: 04_Content_Management_System.php");
            exit;
        } else 
        {
            $_SESSION['flash_message'] = "Error: " . $conn->error;
            $_SESSION['flash_message_type'] = 'danger';
        }
                // Close the statement
        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body 
            {
                font-family: Georgia, 'Times New Roman', Times, serif;
                background-color: #333;
                color: white;
                margin: 0;
                padding: 0;
            }
            .navbar 
            {
                background-color: #000;
                padding: 10px;
                text-align: center;
            }
            .navbar a 
            {
                color: #ffd700;
                text-decoration: none;
                padding: 0 15px;
            }
            .navbar a:hover 
            {
                color: #ff0000;
            }
            .container 
            {
                max-width: 500px;
                margin: 50px auto;
                padding: 20px;
                background-color: #444;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
        </div>

        <div class="container">
            <h2>Register</h2>
            <?php
                        // Display flash message if set
                if (isset($_SESSION['flash_message'])) 
                {
                    $alertType = $_SESSION['flash_message_type'] ?? 'info';
                    echo '<div class="alert alert-' . htmlspecialchars($alertType) . ' alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($_SESSION['flash_message']);
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                    echo '</div>';
                            // Unset flash message after displaying
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_message_type']);
                }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
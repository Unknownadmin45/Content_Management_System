<?php
    session_start();
    require_once '04_Database_Management.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

                // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) 
        {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) 
            {
                $_SESSION['username'] = $username;
                        // Set success message and redirect
                $_SESSION['flash_message'] = "Login successful!";
                http_response_code(302);
                header('Location: 04_Admin_Panel.php');
                exit;
            } 
            else 
            {
                $_SESSION['flash_message'] = "Invalid password.";
            }
        } 
        else 
        {
            $_SESSION['flash_message'] = "No user found with that username.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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
            .alert 
            {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
        </div>

        <div class="container">
            <h2>Login</h2>
            <?php
                        // Display flash message if set
                if (isset($_SESSION['flash_message'])) {
                    $message = $_SESSION['flash_message'];
                    $alertClass = (strpos($message, 'successful') !== false) ? 'alert-success' : 'alert-danger';
                    echo "<div class='alert $alertClass' role='alert'>$message</div>";
                            // Unset flash message after displaying
                    unset($_SESSION['flash_message']);
                }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </body>
</html>
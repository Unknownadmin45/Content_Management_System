<?php
    session_start();
            // Handle automatic logout and flash message
    if (isset($_SESSION['username'])) 
    {
        session_unset();
        session_destroy();
        $_SESSION['flash_message'] = 'You have been logged out successfully.';
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Content Management System</title>
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
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background-color: #444;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
            .welcome-message 
            {
                text-align: center;
                color: darkorange;
            }
            .message 
            {
                text-align: center;
                color: whitesmoke;
            }
            .action-buttons 
            {
                text-align: center;
                margin-top: 20px;
            }
            .action-buttons a 
            {
                background-color: orange;
                color: black;
                border: 1px solid wheat;
                padding: 10px 20px;
                border-radius: 20px;
                text-decoration: none;
                display: inline-block;
            }
            .action-buttons a:hover 
            {
                background-color: black;
                color: orange;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
        </div>

        <div class="container">
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>

            <h1 class="welcome-message">Welcome to Content Management System</h1>
            <p class="message">
                <br>We are excited to have you here!<br>
                This platform allows authors to share their thoughts, ideas, and stories with the world.<br>
                Whether you're a seasoned writer or just starting, we encourage you to express yourself and connect with others through your posts.<br><br><br>
            </p>
            
            <div class="action-buttons">
                <a href="04_Login.php">Login</a>
            </div>
            <div class="action-buttons">
                <a href="04_Register.php">Register</a>
            </div>
            <div class="action-buttons">
                <a href="04_View_All_Posts.php">View All Posts</a>
            </div>
        </div>
    </body>
</html>
<?php
    session_start();
    require_once '04_Database_Management.php';
            // Handle logout
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
        session_unset();
        session_destroy();
        header('Location: 04_Login.php');
        exit();
    }
            // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header('Location: 04_Login.php');
        exit();
    }
            //Establish mysql connection        
    $conn = mysqli_connect("localhost", "root", "", "04_CMS_PHP");

    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }
            // Check for flash message and display it
    $flash_message = '';
    if (isset($_SESSION['flash_message'])) 
    {
        $flash_message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
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
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #444;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
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
            <a href="?logout=true">Logout</a>
        </div>

        <div class="container">
            <?php if ($flash_message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($flash_message); ?>
                </div>
            <?php endif; ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Select an option below to manage your posts.</p>
            <br>

            <div class="action-buttons">
                <a href="04_Create_Post.php">Create New Post</a>
            </div>
            <br>
            <div class="action-buttons">
                <a href="04_Manage_Posts.php">View & Manage My Posts</a>
            </div>
        </div>
    </body>
</html>
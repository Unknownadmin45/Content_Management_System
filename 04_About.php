<?php
            // Start the session
    session_start();
            // Include the database management file with error handling
    try 
    {
        require_once '04_Database_Management.php';
    } catch (Exception $e) 
    {
        echo 'Error including database management file: ' . $e->getMessage();
        exit;
    }
            // Handle logout
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') 
    {
        if (isset($_SESSION['username'])) 
        {
            session_unset();
            session_destroy();
            $_SESSION['flash_message'] = 'You have been logged out successfully.';
            header('Location: 04_Login.php');
            exit();
        } else 
        {
            $_SESSION['flash_message'] = 'You are not logged in.';
            header('Location: 04_Login.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About - CMS Project</title>
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
            .section-title 
            {
                font-size: 28px;
                margin-bottom: 20px;
                color: darkorange;
                text-align: center;
            }
            .highlight 
            {
                color: #ff0000;
                font-weight: bold;
            }
            .list-group-item 
            {
                background-color: #333;
                border: none;
            }
            .list-group-item::before 
            {
                content: "";
                color: #ffd700;
                margin-right: 10px;
            }
            .btn-back 
            {
                background-color: orange;
                color: black;
                border: 1px solid wheat;
                padding: 10px 20px;
                border-radius: 20px;
                text-decoration: none;
                display: inline-block;
                transition: background-color 0.3s, color 0.3s;
            }
            .btn-back:hover 
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
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                </div>
                <?php unset($_SESSION['flash_message']);?>       <!--Unset flash message after displaying-->
            <?php endif; ?>

            <h1 class="section-title">About Our Content Management System</h1>
            <p>Welcome to our <span class="highlight">Content Management System (CMS)</span>, a platform designed to streamline and simplify content creation and management for authors. Our CMS is built with flexibility and user experience in mind, allowing authors to easily create, edit, and manage their content in a structured and efficient way.</p>

            <h2 class="section-title">Key Features</h2>
            <ul class="list-group">
                <li class="list-group-item">Seamless content creation and editing interface</li>
                <li class="list-group-item">User-friendly management tools for organizing content</li>
                <li class="list-group-item">Responsive design, ensuring a great experience on any device</li>
                <li class="list-group-item">Secure user authentication and authorization</li>
                <li class="list-group-item">Efficient database management for content storage</li>
            </ul>

            <h2 class="section-title">Our Mission</h2>
            <p>We aim to empower content creators by providing a platform that is not only powerful but also intuitive to use. Our CMS is crafted with care to ensure that authors can focus on what they do best—creating amazing content—while we handle the technical details. Whether you're a blogger, journalist, or company, our CMS is tailored to meet your needs.</p>

            <p>Thank you for choosing our CMS. We are dedicated to continually improving the platform to better serve our users. If you have any questions or need support, please do not hesitate to reach out to us.</p>

            <div class="text-center">
                <a href="04_Content_Management_System.php" class="btn-back">Return Home</a>
            </div>
        </div>
    </body>
</html>
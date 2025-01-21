<?php
    session_start();
    require_once '04_Database_Management.php';
            // Handle logout
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') 
    {
        session_unset();
        session_destroy();
        header('Location: 04_Login.php');
        exit();
    }

    if (!isset($_SESSION['username'])) 
    {
        header('Location: 04_Login.php');
        exit();
    }
            //Establish mysql connection 
    $conn = new mysqli("localhost", "root", "", "04_CMS_PHP");

    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $content = $_POST['content'];
        $author = $_SESSION['username'];

                // Validate input fields
        if (empty($title) || empty($category) || empty($content)) 
        {
            $_SESSION['flash_message'] = 'Please fill in all fields.';
            header('Location: 04_Create_Post.php');
            exit();
        }
            // SQL Injection protection using prepared statements
        $stmt = $conn->prepare("INSERT INTO posts (title, category, content, author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $category, $content, $author);

        if ($stmt->execute()) 
        {
            $_SESSION['flash_message'] = 'Post created successfully!';
            header('Location: 04_Admin_Panel.php');
            exit();
        } 
        else 
        {
            $_SESSION['flash_message'] = 'Error creating post: ' . $conn->error;
            header('Location: 04_Create_Post.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Post</title>
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
            <a href="?logout=true">Logout</a>
        </div>

        <div class="container">
            <h2>Create Post</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="title">Post Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                    <label for="category">Category:</label>
                    <input type="text" class="form-control" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Post</button>
            </form>
        </div>

        <?php
                    // Check for flash message and display it
            if (isset($_SESSION['flash_message'])) {
                echo '<div class="alert alert-success" role="alert">';
                echo $_SESSION['flash_message'];
                echo '</div>';
                        // Unset flash message after displaying
                unset($_SESSION['flash_message']);
            }
        ?>
    </body>
</html>
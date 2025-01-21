<?php
            // Start the session (if not already started)
    session_start();
            // Include the database management script
    require_once '04_Database_Management.php';
            // Check if the connection is still open
    if (!isset($conn) || !$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }
            // Check if an ID is provided in the URL and is a valid number
    if (isset($_GET['id']) && is_numeric($_GET['id'])) 
    {
        $postId = intval($_GET['id']);
                // Prepare SQL statement to fetch the post by ID
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $post = $result->fetch_assoc();     // Fetch the post details
        } 
        else 
        {
            echo "<div class='alert alert-warning'>No post found with the given ID.</div>";     
            exit;
        }
        $stmt->close();
    } 
    else 
    {
        echo "<div class='alert alert-warning'>Invalid post ID.</div>";
        exit;
    }
    if (isset($conn)) 
    {
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($post['title']); ?></title>
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
            .back-button-container 
            {
                text-align: center; 
                margin-top: 20px;
            }
            .back-button 
            {
                background-color: darkgray; 
                color: whitesmoke;
                border: 1px solid wheat;
                padding: 10px 20px;
                border-radius: 20px;
                text-decoration: none;
                display: inline-block;
            }
            .back-button:hover 
            {
                background-color: black;
                color: blue;
            }
            .post-title 
            {
                color: #ffd700;
            }
            .post-category 
            {
                color: bisque;
            }
            .post-content 
            {
                margin-top: 20px;
            }
            .post-content p:first-of-type 
            {
                text-indent: 2em; 
            }
            .post-meta 
            {
                margin-top: 10px;
                color: #aaa;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
        </div>

        <div class="container">
            <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <h2 class="post-category"><?php echo htmlspecialchars($post['category']); ?></h2>
            <p class="post-meta">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo htmlspecialchars($post['created_at']); ?></p>
            <div class="post-content">
                <?php 
                            // Convert newlines to paragraphs for better formatting
                    $content = htmlspecialchars($post['content']);
                    $content = nl2br($content);
                    $paragraphs = explode('<br />', $content);
                    foreach ($paragraphs as $index => $paragraph) 
                    {
                        if ($index == 0) 
                        {
                            echo "<p style='text-indent: 4em;'>$paragraph</p>";
                        } 
                        else 
                        {
                            echo "<p>$paragraph</p>";
                        }
                    }
                ?>
            </div>
            <br>
            <div class="back-button-container">
                <a href="04_View_All_Posts.php" class="back-button">Return</a>
            </div>
        </div>
    </body>
</html>
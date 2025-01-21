<?php
            // Include the database management script
    require_once '04_Database_Management.php';
            // Check if the connection is still open
    if (!isset($conn) || !$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }
            // SQL query to fetch posts grouped by category, sorted alphabetically
    $sql = "SELECT DISTINCT category FROM posts ORDER BY category ASC";
    $categoriesResult = $conn->query($sql);

    if (!$categoriesResult) 
    {
        die("Error: " . $conn->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View All Posts</title>
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
            h2 
            {
                text-align: center;
                color: #ff8c00;
            }
            .category-section 
            {
                margin-bottom: 30px;
            }
            .category-title 
            {
                background-color: #555;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 15px;
                color: #ffd700;
                text-align: center;
            }
            .card 
            {
                margin-bottom: 20px;
            }
            .card-title 
            {
                color: #ffd700;
            }
            .card-text 
            {
                color: #ccc;
            }
            .text-muted 
            {
                color: #aaa !important;
            }
            .read-more 
            {
                color: #ffd700;
                text-decoration: none;
            }
            .read-more:hover 
            {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
        </div>

        <div class="container">
            <h2>All Posts</h2>
            <?php
                        // Loop through each category
                while ($categoryRow = $categoriesResult->fetch_assoc()) 
                {
                    $category = $categoryRow['category']; 
                            // Fetch posts for the current category
                    $postsSql = "SELECT id, title, author, content, created_at FROM posts WHERE category = ? ORDER BY created_at DESC";
                    $stmt = $conn->prepare($postsSql);
                    $stmt->bind_param("s", $category);
                    $stmt->execute();
                    $postsResult = $stmt->get_result();
                    
                    if ($postsResult->num_rows > 0) 
                    {
                        echo "<div class='category-section'>";
                        echo "<h3 class='category-title'>" . htmlspecialchars($category) . "</h3>";
                                // Display posts for this category
                        while ($post = $postsResult->fetch_assoc()) 
                        {
                            echo "<div class='card'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($post['title']) . "</h5>";
                            echo "<p class='text-muted'>By " . htmlspecialchars($post['author']) . " on " . htmlspecialchars($post['created_at']) . "</p>";
                            echo "<p class='card-text'>" . htmlspecialchars(substr($post['content'], 0, 150)) . "... </p>";
                            echo "<a href='04_View_Post.php?id=" . htmlspecialchars($post['id']) . "' class='read-more'>Read More</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                            // Close the statement
                    $stmt->close();
                }
                        // Close the connection
                if (isset($conn)) 
                {
                    $conn->close();
                }
            ?>
        </div>
    </body>
</html>
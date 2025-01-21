<?php
    require_once '04_Database_Management.php';

    if (isset($_GET['id'])) 
    {
        $postId = (int) $_GET['id'];        // Cast to integer to prevent SQL injection

        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '';
        $db_name = '04_CMS_PHP';

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        if ($conn->connect_error) 
        {
            die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
        }

        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
        if (!$stmt) 
        {
            die(json_encode(array('error' => 'Prepare failed: ' . $conn->error)));
        }

        $stmt->bind_param("i", $postId);
        if (!$stmt->execute()) 
        {
            die(json_encode(array('error' => 'Execute failed: ' . $stmt->error)));
        }

        $result = $stmt->get_result();
        if (!$result) 
        {
            die(json_encode(array('error' => 'Get result failed: ' . $stmt->error)));
        }

        $post = $result->fetch_assoc();
        if (!$post) 
        {
            echo json_encode(array('error' => 'Post not found'));
        } 
        else 
        {
            echo json_encode($post);
        }

        $stmt->close();
        $conn->close();
    } 
    else 
    {
        echo json_encode(array('error' => 'Invalid request'));
    }
?>
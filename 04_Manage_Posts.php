<?php
    session_start();
    require_once '04_Database_Management.php';

    if (!isset($_SESSION['username'])) 
    {
        header('Location: 04_Login.php');
        exit();
    }

    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = '04_CMS_PHP';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $flash_message = '';
    $flash_message_type = '';

    if (isset($_SESSION['flash_message'])) 
    {
        $flash_message = $_SESSION['flash_message'];
        $flash_message_type = $_SESSION['flash_message_type'];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_message_type']);
    }

    if (isset($_GET['logout'])) 
    {
        session_unset();
        session_destroy();
        $_SESSION['flash_message'] = "You have been logged out successfully.";
        $_SESSION['flash_message_type'] = "success";
        header('Location: 04_Content_Management_System.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        try 
        {
            if (isset($_POST['id'])) 
            {
                $postId = $_POST['id'];
                if (isset($_POST['action'])) 
                {
                    if ($_POST['action'] === 'update') 
                    {
                        $title = $_POST['title'];
                        $category = $_POST['category'];
                        $content = $_POST['content'];
                        $stmt = $conn->prepare("UPDATE posts SET title = ?, category = ?, content = ? WHERE id = ?");
                        $stmt->bind_param("sssi", $title, $category, $content, $postId);
                        if ($stmt->execute()) 
                        {
                            $_SESSION['flash_message'] = "Post updated successfully.";
                            $_SESSION['flash_message_type'] = "success";
                            echo 'success';
                        } 
                        else 
                        {
                            $_SESSION['flash_message'] = "Error updating post.";
                            $_SESSION['flash_message_type'] = "danger";
                            echo 'error';
                        }
                        $stmt->close();
                    } 
                    elseif ($_POST['action'] === 'delete') 
                    {
                        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
                        $stmt->bind_param("i", $postId);
                        if ($stmt->execute()) 
                        {
                            $_SESSION['flash_message'] = "Post deleted successfully.";
                            $_SESSION['flash_message_type'] = "success";
                            echo 'success';
                        } 
                        else 
                        {
                            $_SESSION['flash_message'] = "Error deleting post.";
                            $_SESSION['flash_message_type'] = "danger";
                            echo 'error';
                        }
                        $stmt->close();
                    }
                }
                $conn->close();
                exit();
            }
        } 
        catch (Exception $e) 
        {
            $_SESSION['flash_message'] = "An error occurred: " . $e->getMessage();
            $_SESSION['flash_message_type'] = "danger";
            echo 'error: ' . $e->getMessage();
        }
    }

    if (isset($_GET['id'])) 
    {
        $postId = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        echo json_encode($post);
        exit();
    }

    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT id, title, category FROM posts WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Posts</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
            .action-buttons a 
            {
                background-color: orange;
                color: black;
                border: 1px solid wheat;
                padding: 10px 20px;
                border-radius: 20px;
                text-decoration: none;
                display: inline-block;
                margin: 5px 0;
            }
            .action-buttons a:hover 
            {
                background-color: black;
                color: orange;
            }
            .logout-btn 
            {
                color: red;
            }
            .post-link 
            {
                cursor: pointer;
                color: #ffd700;
            }
            .post-link:hover 
            {
                color: #ff0000;
                text-decoration: underline;
            }
            .post-actions 
            {
                margin-top: 10px;
            }
            .post-actions button 
            {
                margin-right: 10px;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 18px;
            }
            .post-actions button:hover 
            {
                color: #ff0000;
            }
            .alert 
            {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <a href="04_Content_Management_System.php">Home</a>
            <a href="04_About.php">About</a>
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>

        <div class="container">
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flash_message_type); ?>" role="alert">
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

            <h3>My Posts</h3>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div>
                        <h4 class="post-link" data-id="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                        <div class="post-actions">
                            <button class="edit-post" data-id="<?php echo $row['id']; ?>" title="Edit Post">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-post" data-id="<?php echo $row['id']; ?>" title="Delete Post">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts found. <a href="04_Create_Post.php">Create a new post.</a></p>
            <?php endif; ?>
            <br><br>
            <div class="back-button-container">
                <a href="04_View_All_Posts.php" class="back-button">All Posts</a>
            </div>
        </div>

                <!-- View Post Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Post Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 id="modalTitle"></h4>
                        <p id="modalCategory"></p>
                        <p id="modalContent"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

                <!-- Edit Post Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="editPostId">
                            <div class="form-group">
                                <label for="editTitle">Title</label>
                                <input type="text" class="form-control" id="editTitle" required>
                            </div>
                            <div class="form-group">
                                <label for="editCategory">Category</label>
                                <input type="text" class="form-control" id="editCategory" required>
                            </div>
                            <div class="form-group">
                                <label for="editContent">Content</label>
                                <textarea class="form-control" id="editContent" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

                <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this post?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() 
            {
                        // Handle post link click
                $('.post-link').on('click', function() 
                {
                    var postId = $(this).data('id');
                    $.get('04_Manage_Posts.php', { id: postId }, function(data) 
                    {
                        var post = JSON.parse(data);
                        $('#modalTitle').text(post.title);
                        $('#modalCategory').text(post.category);
                        $('#modalContent').text(post.content);
                        $('#viewModal').modal('show');
                    });
                });
                        // Handle edit button click
                $('.edit-post').on('click', function() 
                {
                    var postId = $(this).data('id');
                    $.get('04_Manage_Posts.php', { id: postId }, function(data) 
                    {
                        var post = JSON.parse(data);
                        $('#editPostId').val(post.id);
                        $('#editTitle').val(post.title);
                        $('#editCategory').val(post.category);
                        $('#editContent').val(post.content);
                        $('#editModal').modal('show');
                    });
                });
                        // Handle edit form submission
                $('#editForm').on('submit', function(e) 
                {
                    e.preventDefault();
                    var postId = $('#editPostId').val();
                    var title = $('#editTitle').val();
                    var category = $('#editCategory').val();
                    var content = $('#editContent').val();
                    $.post('04_Manage_Posts.php', 
                    {
                        action: 'update',
                        id: postId,
                        title: title,
                        category: category,
                        content: content
                    }, 
                    function(response) 
                    {
                        if (response === 'success') 
                        {
                            $('#editModal').modal('hide');
                            location.reload();
                        } 
                        else 
                        {
                            alert('Error updating post.');
                        }
                    });
                });
                        // Handle delete button click
                $('.delete-post').on('click', function() 
                {
                    var postId = $(this).data('id');
                    $('#deleteModal').data('id', postId).modal('show');
                });
                        // Handle delete confirmation
                $('#confirmDelete').on('click', function() 
                {
                    var postId = $('#deleteModal').data('id');
                    $.post('04_Manage_Posts.php', 
                    {
                        action: 'delete',
                        id: postId
                    }, 
                    function(response) 
                    {
                        if (response === 'success') 
                        {
                            $('#deleteModal').modal('hide');
                            location.reload();
                        } 
                        else 
                        {
                            alert('Error deleting post.');
                        }
                    });
                });
            });
        </script>
    </body>
</html>
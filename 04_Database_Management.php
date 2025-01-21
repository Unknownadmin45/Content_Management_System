<?php
            // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
            // Create a new MySQLi object with error reporting
    $conn = new mysqli($servername, $username, $password);
            // Check for connection errors
    if ($conn->connect_errno) 
    {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
            // Set character set to UTF-8
    $conn->set_charset('utf8');
            // Create database if not exists
    $dbname = "04_CMS_PHP";
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (!$conn->query($sql)) 
    {
        throw new Exception("Error creating database: " . $conn->error);
    }
            // Select the database
    if (!$conn->select_db($dbname)) 
    {
        throw new Exception("Error selecting database: " . $conn->error);
    }
            // Create necessary tables if they do not exist
    $sql = "CREATE TABLE IF NOT EXISTS posts 
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    author VARCHAR(255) NOT NULL,
                    category VARCHAR(300) NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

    if (!$conn->query($sql))    
    {
        throw new Exception("Error creating tables: " . $conn->error);
    }
            // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users 
                (
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password_hash VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";

    if (!$conn->query($sql)) 
    {
        throw new Exception("Error creating tables: " . $conn->error);
    }
?>
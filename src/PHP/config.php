<?php
    // Kết nối cơ sở dữ liệu
    $host = 'localhost';
    $db_name = 'ltw_database';
    $username = 'root';
    $password = '';
    
    // Tạo kết nối MySQLi
    $conn = new mysqli($host, $username, $password, $db_name);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }
?>

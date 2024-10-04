<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập và có vai trò admin chưa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin, chuyển hướng về trang đăng nhập
    header("Location: login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styleAdmin.css">
</head>
<body>
    <aside>
        <h2>Admin</h2>
        <ul>
            <li><a href="./userManageBE.php">Quản lý người dùng</a></li>
            <li><a href="./packageManageBE.php">Quản lý gói tập</a></li>
            <li><a href="#!">Xem hóa đơn</a></li>
            <li><a href="#!">Xem doanh thu</a></li>
        </ul>  
        
       <form action="./clear_session.php" method="POST">
            <button type="submit" class="btn">Log out</button>
       </form>
    </aside>

</body>
</html>
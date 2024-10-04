<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập và có vai trò user chưa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    // Nếu không phải user, chuyển hướng về trang đăng nhập
    header("Location: login.html");
    exit();
}
?>


<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "GymManagement";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    //Truy vấn để lấy ra RegistrationDate, và PackageID
    $sql = "SELECT RegistrationDate, PackageID FROM registrations WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['ID']);

    $stmt->execute();
    $result_tmp = $stmt->get_result();
    $registrations = [];
while ($row1 = $result_tmp->fetch_assoc()) {
    $PackageID = $row1['PackageID'];

    // Dùng PackageID vừa lấy ra để truy vấn lấy PackageName, Price
    $sql2 = "SELECT PackageName, Price FROM packages WHERE PackageID = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $PackageID);

    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();

    // Lưu thông tin vào mảng để hiển thị sau này
    $registrations[] = [
        'PackageName' => $row2['PackageName'],
        'Price' => $row2['Price'],
        'RegistrationDate' => $row1['RegistrationDate']
    ];
}
    

    // //Đóng statement
    // $stmt->close();
    // $stmt2->close();
?>



<?php

// Kiểm tra nếu session tồn tại
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    echo "Username: " . $_SESSION['username'] . "<br>";
    echo "Role: " . $_SESSION['role'];
} else {
    echo "No session data available.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styleAdmin.css">
</head>
<body>
    <div class="container">
        <h1>Xin chào <?php  echo $_SESSION['username'] ?></h1>
        <h2>Gói tập đã đăng ký</h2>
        <table>
            <thead>
                <tr>
                    <th>Tên gói tập</th>
                    <th>Giá gói tập</th>
                    <th>Thời gian đăng ký</th>
                    <!-- <th>Trạng thái</th> -->
                </tr>
            </thead>
            <tbody>
            <?php if (count($registrations) > 0) { ?>
                    <?php foreach ($registrations as $registration) { ?>
                    <tr>
                        <td><?php echo $registration['PackageName']; ?></td>
                        <td><?php echo $registration['Price']; ?></td>
                        <td><?php echo $registration['RegistrationDate']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php }else {?>
                        <tr>
                            <td colspan=3>Bạn chưa đăng ký gói tập</td>
                        </tr>
                <?php }?>
            </tbody>
        </table>
        <a href="./registration.php">
            <button class="btn">Đăng ký gói tập</button>
        </a>
         
        <form action="./clear_session.php" method="POST"> 
            <button type="submit" class="btn">Log out</button>
        </form>
       
    </div>
</body>
</html>
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $packageID = $_POST['packageID'];
    $status = $_POST['status'];

    // Lấy userID từ session (giả sử người dùng đã đăng nhập)
    if (isset($_SESSION['ID'])) {
        $ID = $_SESSION['ID'];
    } else {
        echo "<script>alert('Bạn cần đăng nhập trước khi đăng ký gói tập'); window.location.href = 'login.php';</script>";
        exit();
    }

    // Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "gymmanagement");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $status = "active";
    // Chèn thông tin vào bảng registrations
    $sql = "INSERT INTO registrations (UserID, PackageID, RegistrationDate, Status) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $ID, $packageID, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Đăng ký thành công'); window.location.href = 'user.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $stmt->error . "'); window.location.href = 'registration.php';</script>";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>

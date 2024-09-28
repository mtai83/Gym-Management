<?php
// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GymManagement";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username-input'];
    $phone = $_POST['sdt-input'];
    $password = $_POST['passwd-input'];
    $confirm_password = $_POST['re-passwd'];

    // Kiểm tra mật khẩu có khớp hay không
    if ($password !== $confirm_password) {
        echo "Mật khẩu xác nhận không khớp!";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $sql = "INSERT INTO Users (username, sdt, password) VALUES (?, ?, ?)";
        // Sử dụng prepared statement để tránh SQL Injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $phone, $hashed_password);
        $error = $conn->error;
        // Thực thi và kiểm tra
        if ($stmt->execute()) {
            echo "<script>alert('Đăng ký thành công!'); window.location.href = '../pages/login.html';</script>";
        } else {
            echo "<script>alert('Lỗi " . addslashes($error) . "'); window.location.href = '../pages/SignUp.html';</script>";
            exit;
        }

        // Đóng câu lệnh và kết nối
        $stmt->close();
    }
}

$conn->close();
?>

<?php 

    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "GymManagement";

    // Tạo kết nối
    $conn =  new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Xử lý khi form được submit   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username-input'];
        $password = $_POST['passwd-input'];

     
        $sql = "SELECT username, password FROM Users WHERE username = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows >0){
            $row = $result->fetch_assoc();

            if(password_verify($password, $row['password'])){
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                header("Location: ../index.php");
                exit;

            } else {
                echo "<script>alert('Sai mật khẩu'); window.location.href = '../pages/login.html';</script>";
            }

        }else {
            echo "<script>alert('Tên đăng nhập không tồn tại!'); window.location.href = '../pages/login.html';</script>";
        }
    }
?>
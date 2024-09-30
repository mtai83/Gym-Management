<?php
// Bắt đầu session để kiểm tra người dùng đã đăng nhập hay chưa
session_start();

// Kiểm tra người dùng đã đăng nhập chưa (giả sử bạn lưu session khi đăng nhập)
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit;
// }

// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GymManagement";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm người dùng mới
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    $sdt = $_POST['sdt'];
    $role = $_POST['role'];

    $sql = "INSERT INTO Users (username, password, sdt, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $username, $password, $sdt, $role);
    
    if ($stmt->execute()) {
        echo "<script>alert('Thêm người dùng thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Xóa người dùng
if (isset($_GET['delete'])) {
    $username = $_GET['delete'];

    $sql = "DELETE FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa người dùng thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Lấy danh sách người dùng
$sql = "SELECT sdt, username, role FROM Users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styleAdmin.css">
</head>
<body>
    <div class="container">
        <a href="admin.html">➦ Back to Dashboard</a>
        <h1>Quản lý người dùng</h1>
        <h2>Thêm người dùng mới</h2>
        <!-- Form thêm người dùng mới -->
        <form method="POST" action="../pages/userManageBE.php">
            <div class="row">
                <label for="username">Tên đăng nhập: </label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="row">
                <label for="password">Mật khẩu: </label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="row">
                <label for="sdt">Số điện thoại: </label>
                <input type="tel" id="sdt" name="sdt" pattern="[0-9]{10}" required>
            </div>
            <div class="row">
                <label for="role">Vai trò: </label>
                <select id="role" name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn">Thêm người dùng</button>
        </form>

        <h2>Danh sách người dùng</h2>
        <table>
            <thead>
                <tr>
                    <th>Số điện thoại</th>
                    <th>Tên đăng nhập</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['sdt']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>
                                <a href="edit_user.php?sdt=<?php echo $row['sdt']; ?>">Sửa</a> |
                                <a href="userManageBE.php?delete=<?php echo $row['username']; ?>" onclick="return confirm('Bạn có chắc muốn xóa người dùng này không?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Không có người dùng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

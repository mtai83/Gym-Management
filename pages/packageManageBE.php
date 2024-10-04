<?php 
    // Bắt đầu session để kiểm tra người dùng đã đăng nhập hay chưa
    session_start();

    // Kiểm tra người dùng đã đăng nhập chưa (giả sử bạn lưu session khi đăng nhập)
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
        header("Location: ./login.html");
        exit;
    }else

    //Kết nối tới csdl
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "GymManagement";

    $conn = new mysqli($servername, $username, $password, $dbname);

    //Kiểm tra kết nối
    if($conn->connect_error){
        die("Kết nối thất bại : " . $conn->connect_error);
    }

    //Thêm gói tập mới
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_package"])){
         $packageName = $_POST['packageName'];
        $packagePrice = $_POST['packagePrice'];
        $packageTime = $_POST['packageTime'];

        $sql = "INSERT INTO packages(PackageName, Price, PackageTime) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $packageName, $packagePrice, $packageTime);

        if($stmt->execute()){
            echo "<script>
                alert('Thêm gói tập thành công');
                window.location.href = '../pages/packageManageBE.php';
              </script>";
        }else {
            echo "<script>alert('Lỗi:" .$conn->error  .")</script>";
        }

        $stmt->close();
    }
       
    // Xem danh sách gói tập
    $sql = "SELECT PackageName, Price, PackageTime FROM packages";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Package Manage</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/styleAdmin.css">
    </head>
    <body>
        <div class="container">
        <a href="admin.php">➦ Back to Dashboard</a>
        <h1>Quản lý gói tập</h1>
        <h2>Thêm gói tập mới</h2>
    <!-- Thêm gói tập -->
        <form action="../pages/packageManageBE.php" method="POST">
            <div class="row">
                <label for="packageName">Tên gói tập</label>
                <input type="text" id="packageName" name="packageName" require>
            </div>
            <div class="row">
                <label for="packagePrice">Giá gói tập</label>
                <input type="number" min="0" id="packagePrice" name="packagePrice" require>
            </div>
            <div class="row">
                <label for="packageTime">Thời gian gói tập (Ngày)</label>
                <input type="number" min="1" id="packageTime" name="packageTime" require>
            </div>
            <button type="submit" name="add_package" class="btn">Thêm gói tập</button>
        </form>

        <h2>Danh sách các gói tập</h2>
        <table>
            <thead>
                <tr>
                    <th>Tên gói tập</th>
                    <th>Giá gói tập</th>
                    <th>Thời gian gói tập (Ngày)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['PackageName']; ?></td> 
                            <td><?php echo $row['Price']; ?></td> 
                            <td><?php echo $row['PackageTime']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="3">Không có gói tập nào</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </body>
    </html>
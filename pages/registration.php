<?php
session_start(); // Bắt đầu session


header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); 

// Kiểm tra nếu session tồn tại
if (isset($_SESSION['username']) && isset($_SESSION['role']) && isset($_SESSION['ID'])) {
    echo "Username: " . $_SESSION['username'] . "<br>";
    echo "Role: " . $_SESSION['role'] . "<br>";
    echo "ID: " . $_SESSION['ID'];
} else {
    echo "No session data available.";
}
?>

<?php 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
        header("Location: ./login.html");
        exit;
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Gói Tập</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styleAdmin.css">
    <script>
        function updatePrice() {
            var packageSelect = document.getElementById("package");
            var price = packageSelect.options[packageSelect.selectedIndex].getAttribute("data-price");
            document.getElementById("price").value = price;
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="user.php">➦ Back to Home</a>
        <h1>Đăng Ký Gói Tập</h1>
        <form action="registerBE.php" method="POST">
            <div class="row">
                <label for="package">Chọn Gói Tập: </label>
                <select name="packageID" id="package" onchange="updatePrice()">
                    <!-- Các gói tập sẽ được load từ CSDL và hiển thị dưới dạng options -->
                    <?php
                        // Kết nối CSDL và lấy danh sách các gói tập
                        $conn = new mysqli("localhost", "root", "", "gymmanagement");
                        if ($conn->connect_error) {
                            die("Kết nối thất bại: " . $conn->connect_error);
                        }
                        $sql = "SELECT PackageID, PackageName, Price FROM packages";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['PackageID'] . "' data-price='" . $row['Price'] . "'>" . $row['PackageName'] . "</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select>
            </div>

            <div class="row">
                <label for="price">Giá Gói Tập: </label>
                <input type="text" id="price" name="price" readonly>
            </div>

            <button type="submit" class="btn">Đăng Ký</button>
        </form>
    </div>
</body>
</html>

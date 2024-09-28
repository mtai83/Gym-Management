<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if(isset($_GET['page'])){
            $page = $_GET['page'];
            if($page == 'login')
                include 'pages/login.html';
        } else {
            include 'pages/index.html';
        }
    ?>
</body>
</html>
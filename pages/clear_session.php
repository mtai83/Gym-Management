<?php 
    session_unset();
    session_destroy();

    // Ngăn chặn caching
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    header("Location: ../index.php");
?>
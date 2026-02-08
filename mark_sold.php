<?php
include 'includes/header.php';
date_default_timezone_set('Asia/Kolkata'); 
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $product_id = intval($_GET['id']);
    $seller_id = $_SESSION['user_id'];

    // Security Check: Sirf wahi delete kar sakta hai jisne upload kiya
    $sql = "UPDATE products SET status = 1 WHERE id = $product_id AND seller_id = $seller_id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=sold");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
}
?>
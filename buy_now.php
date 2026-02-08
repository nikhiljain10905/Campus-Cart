<?php
include 'includes/db.php';
session_start();
date_default_timezone_set('Asia/Kolkata'); 

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Turant Status=1 (Sold) aur Price=Max Price set karo
    $sql = "UPDATE products SET price = max_price, last_bidder_id = $user_id, status = 1 WHERE id = $id AND status = 0";
    
    if (mysqli_query($conn, $sql)) {
        // Success: View page par bhejo details dekhne ke liye
        echo "<script>alert('Congratulations! Item Purchased.'); window.location='view_product.php?id=$id';</script>";
    } else {
        echo "Something went wrong.";
    }
} else {
    header("Location: index.php");
}
?>
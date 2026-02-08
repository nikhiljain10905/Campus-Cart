<?php
include 'includes/db.php';
session_start();
date_default_timezone_set('Asia/Kolkata'); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    
    $product_id = intval($_POST['product_id']);
    $increment = intval($_POST['bid_amount']);
    $user_id = $_SESSION['user_id'];

    // 1. Product Fetch karo
    $sql = "SELECT price, max_price, seller_id, created_at, status FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if ($product && $product['status'] == 0) {
        
        // 2. TIME CHECK (1 Hour Limit)
        $created_time = strtotime($product['created_at']);
        $end_time = $created_time + 3600; // 60 mins * 60 secs
        
        if (time() > $end_time) {
            echo "<script>alert('Time Up! Bidding closed.'); window.location='index.php';</script>";
            exit();
        }

        // Seller khud bid nahi kar sakta
        if ($product['seller_id'] == $user_id) {
            echo "<script>alert('Apne item par bid nahi laga sakte!'); window.location='index.php';</script>";
            exit();
        }

        // 3. New Price Calculate karo
        $new_price = $product['price'] + $increment;
        $status = 0; // Default Available

        // 4. AUTO-SELL LOGIC
        // Agar nayi bid Max Price se zyada ya barabar hai -> Turant Sold
        if ($new_price >= $product['max_price']) {
            $new_price = $product['max_price']; // Cap price at max
            $status = 1; // Sold
        }

        // 5. Update DB
        $updateSql = "UPDATE products SET price = $new_price, last_bidder_id = $user_id, status = $status WHERE id = $product_id";
        
        if (mysqli_query($conn, $updateSql)) {
            if ($status == 1) {
                echo "<script>alert('Auto-Sold! Bid reached Max Price.'); window.location='view_product.php?id=$product_id';</script>";
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: login.php");
}
?>
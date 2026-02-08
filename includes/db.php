<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
// Database name ab 'campuscart' hai
$conn = mysqli_connect("localhost", "root", "", "campuscart");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
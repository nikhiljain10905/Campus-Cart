<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Campus Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Join Campus Cart</h2>
        
        <?php
        if (isset($_POST['register'])) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);

            // IIITJ Email Check
            if (!strpos($email, '@iiitdmj.ac.in')) {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4'>Sirf IIITJ email chalega bhai!</div>";
            } else {
                $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$pass', '$phone')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
                } else {
                    echo "<p class='text-red-500'>Error: Email already used!</p>";
                }
            }
        }
        ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required class="w-full p-2 border mb-3 rounded">
            <input type="email" name="email" placeholder="College Email (@iiitdmj.ac.in)" required class="w-full p-2 border mb-3 rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border mb-3 rounded">
            <input type="text" name="phone" placeholder="WhatsApp Number" required class="w-full p-2 border mb-4 rounded">
            <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded font-bold">Register</button>
        </form>
        <p class="mt-4 text-center text-sm">Already have an account? <a href="login.php" class="text-blue-500 font-bold">Login</a></p>
    </div>
</body>
</html>
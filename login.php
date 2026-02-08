<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Campus Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-green-600">Login</h2>

        <?php
        if (isset($_POST['login'])) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = $_POST['password'];

            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php");
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4'>Invalid Email or Password!</div>";
            }
        }
        ?>

        <form method="POST">
            <input type="email" name="email" placeholder="College Email" required class="w-full p-2 border mb-3 rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border mb-4 rounded">
            <button type="submit" name="login" class="w-full bg-green-600 hover:bg-green-700 text-white p-2 rounded font-bold">Login</button>
        </form>
        <p class="mt-4 text-center text-sm">New user? <a href="register.php" class="text-blue-500 font-bold">Register</a></p>
    </div>
</body>
</html>
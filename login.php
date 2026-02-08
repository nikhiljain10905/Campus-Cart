

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Campus Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <?php include 'includes/header.php'; ?> 

    <div class="flex-grow flex items-center justify-center">
        
        <div class="bg-white p-8 rounded-xl shadow-lg w-96 border border-gray-200">
            <h2 class="text-3xl font-extrabold mb-6 text-center text-green-700">Login</h2>

            <?php
            if (isset($_POST['login'])) {
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = $_POST['password'];

                $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
                $user = mysqli_fetch_assoc($result);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    echo "<script>window.location.href='index.php';</script>";
                } else {
                    echo "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm rounded'>Invalid Email or Password!</div>";
                }
            }
            ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="student@iiitdmj.ac.in" required 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <button type="submit" name="login" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg font-bold transition shadow-md">
                    Login securely
                </button>
            </form>
            
            <p class="mt-6 text-center text-sm text-gray-600">
                New to Campus Cart? <a href="register.php" class="text-green-600 font-bold hover:underline">Create Account</a>
            </p>
        </div>

    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Campus Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include 'includes/header.php'; ?>

    <div class="flex-grow flex items-center justify-center">
        
        <div class="bg-white p-8 rounded shadow-md w-96 border border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Join Campus Cart</h2>
            
            <?php
            if (isset($_POST['register'])) {
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                // Security: Password ko HASH karo
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $phone = mysqli_real_escape_string($conn, $_POST['phone']);

                // IIITJ Email Validation
                if (!strpos($email, '@iiitdmj.ac.in')) {
                    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm'>
                            Sirf <b>@iiitdmj.ac.in</b> email chalega bhai!
                          </div>";
                } else {
                    // Check duplicate email
                    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
                    if(mysqli_num_rows($check) > 0) {
                        echo "<div class='bg-yellow-100 text-yellow-700 px-4 py-2 rounded mb-4 text-sm'>Email already registered. Login karo!</div>";
                    } else {
                        $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$pass', '$phone')";
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Registration Successful! Ab Login karo.'); window.location='login.php';</script>";
                        } else {
                            echo "<p class='text-red-500 text-center mb-4'>Error: Something went wrong!</p>";
                        }
                    }
                }
            }
            ?>

            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required class="w-full p-2 border mb-3 rounded focus:outline-blue-500">
                <input type="email" name="email" placeholder="College Email (@iiitdmj.ac.in)" required class="w-full p-2 border mb-3 rounded focus:outline-blue-500">
                <input type="password" name="password" placeholder="Password" required class="w-full p-2 border mb-3 rounded focus:outline-blue-500">
                <input type="text" name="phone" placeholder="WhatsApp Number" required class="w-full p-2 border mb-4 rounded focus:outline-blue-500">
                
                <button type="submit" name="register" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded font-bold transition">
                    Register
                </button>
            </form>
            
            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account? <a href="login.php" class="text-blue-500 font-bold hover:underline">Login</a>
            </p>
        </div>

    </div>

</body>
</html>
<?php 
include 'includes/db.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
date_default_timezone_set('Asia/Kolkata'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell Item - Campus Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-blue-900 text-center">Sell Item (Auction Mode)</h2>

        <?php
        if (isset($_POST['sell'])) {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $price = intval($_POST['price']);
            $max_price = intval($_POST['max_price']);
            $desc = mysqli_real_escape_string($conn, $_POST['desc']);
            // --- NEW: Tags ko capture karo ---
            $tags = mysqli_real_escape_string($conn, $_POST['tags']); 
            $seller_id = $_SESSION['user_id'];

            $image_name = time() . '_' . $_FILES['image']['name'];
            $target_path = "uploads/" . $image_name;

            if ($max_price <= $price) {
                echo "<p class='text-red-500 text-center mb-4'>Error: Buy Now Price must be higher than Starting Bid.</p>";
            } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                
                // --- NEW: Query mein tags add karo ---
                $sql = "INSERT INTO products (seller_id, title, price, max_price, description, tags, image, status) 
                        VALUES ('$seller_id', '$title', '$price', '$max_price', '$desc', '$tags', '$image_name', 0)";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Auction Started!'); window.location='index.php';</script>";
                } else {
                    echo "<p class='text-red-500'>DB Error: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p class='text-red-500'>Image Upload Failed.</p>";
            }
        }
        ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700">Product Name</label>
                <input type="text" id="titleInput" name="title" required class="w-full p-2 border rounded focus:outline-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700">Start Bid (₹)</label>
                    <input type="number" name="price" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Max Price (₹)</label>
                    <input type="number" name="max_price" required class="w-full p-2 border rounded">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Description</label>
                <textarea id="descInput" name="desc" required class="w-full p-2 border rounded h-24"></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 flex justify-between items-center">
                    Tags (Hashtags)
                    <button type="button" onclick="generateAiTags()" id="aiBtn" class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full hover:bg-purple-200 transition flex items-center">
                        ✨ Generate with AI
                    </button>
                </label>
                <input type="text" id="tagsOutput" name="tags" readonly placeholder="Fill title & desc, then click Generate ✨" class="w-full p-2 border rounded bg-gray-50 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Photo</label>
                <input type="file" name="image" accept="image/*" required class="w-full p-2 border rounded bg-gray-50">
            </div>
            
            <button type="submit" name="sell" class="w-full bg-blue-900 text-white p-3 rounded-lg font-bold hover:bg-blue-800 transition">Start Auction 🔨</button>
        </form>
        <a href="index.php" class="block text-center mt-4 text-blue-500 text-sm">Cancel</a>
    </div>

    <script>
    async function generateAiTags() {
        const title = document.getElementById('titleInput').value;
        const desc = document.getElementById('descInput').value;
        const outputBox = document.getElementById('tagsOutput');
        const btn = document.getElementById('aiBtn');

        if(title.length < 3 || desc.length < 5) {
            alert("Please fill in a proper Title and Description first!");
            return;
        }

        // Button styling change for loading state
        btn.innerHTML = "⏳ Generating...";
        btn.disabled = true;
        outputBox.placeholder = "Asking Gemini API...";

        try {
            const response = await fetch('generate_tags.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: title, desc: desc })
            });

            const data = await response.json();

            if(data.tags) {
                outputBox.value = data.tags;
                btn.innerHTML = "✅ Generated!";
            } else {
                outputBox.placeholder = "Error generating tags.";
                btn.innerHTML = "❌ Failed";
            }

        } catch (error) {
            console.error('Error:', error);
            outputBox.placeholder = "Connection Error.";
            btn.innerHTML = "❌ Error";
        }

        // Reset button after 2 seconds
        setTimeout(() => {
            btn.innerHTML = "✨ Generate with AI";
            btn.disabled = false;
        }, 2000);
    }
    </script>
</body>
</html>
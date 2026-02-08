<?php 
include 'includes/db.php'; 
// Timezone check
date_default_timezone_set('Asia/Kolkata');

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = intval($_GET['id']);

// Fetch Product + Seller + Last Bidder
$sql = "SELECT p.*, u.name as seller_name, u.phone, b.name as bidder_name 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        LEFT JOIN users b ON p.last_bidder_id = b.id 
        WHERE p.id = $id";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) { echo "<script>alert('Product not found!'); window.location='index.php';</script>"; exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?> - Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <nav class="bg-blue-900 p-4 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">🛒 Campus Cart</a>
            <a href="index.php" class="text-sm hover:underline">← Back to Auctions</a>
        </div>
    </nav>

    <div class="container mx-auto mt-10 p-4 flex-grow">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                
                <div class="bg-gray-100 h-96 md:h-auto flex items-center justify-center relative group">
                    <img src="uploads/<?php echo $row['image']; ?>" class="w-full h-full object-contain p-4">
                    
                    <?php if ($row['status'] == 1): ?>
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                            <div class="text-center">
                                <span class="block bg-red-600 text-white text-3xl font-black px-6 py-2 rounded-lg transform -rotate-12 border-4 border-white shadow-xl">SOLD</span>
                                <p class="text-white font-bold mt-4">Winner: <?php echo htmlspecialchars($row['bidder_name']); ?></p>
                            </div>
                        </div>
                    <?php elseif ($row['status'] == 2): ?>
                         <div class="absolute inset-0 bg-gray-800/80 flex items-center justify-center backdrop-blur-sm">
                            <span class="text-white text-3xl font-bold px-6 py-2 border-2 border-white rounded">EXPIRED</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-8 md:p-12 flex flex-col justify-between">
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>
                        
                        <div class="bg-blue-50 p-4 rounded-xl mb-6 border-l-4 border-blue-600">
                            <p class="text-xs font-bold text-gray-500 uppercase">Sold By</p>
                            <p class="text-xl font-bold text-blue-900"><?php echo htmlspecialchars($row['seller_name']); ?></p>
                            <p class="text-lg text-green-700 font-mono font-bold mt-1">📞 <?php echo htmlspecialchars($row['phone']); ?></p>
                        </div>

                        <h3 class="font-bold text-gray-700 mb-2">Description:</h3>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6">
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </p>

                        <?php if (!empty($row['tags'])): ?>
                            <div class="mb-8">
                                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">AI Generated Tags ✨</h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php 
                                    $tags_array = explode(',', $row['tags']);
                                    foreach ($tags_array as $tag): 
                                        $tag = trim($tag); if(!empty($tag)):
                                    ?>
                                        <span class="bg-purple-100 text-purple-700 text-sm px-3 py-1 rounded-full font-semibold border border-purple-200"><?php echo htmlspecialchars($tag); ?></span>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-100 p-4 rounded-lg text-center border border-gray-200">
                                <p class="text-xs text-gray-500 font-bold uppercase">Winning/Current Bid</p>
                                <p class="text-2xl font-black text-blue-900">₹<?php echo $row['price']; ?></p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg text-center border border-gray-200 opacity-60">
                                <p class="text-xs text-gray-500 font-bold uppercase">Buy Now Price</p>
                                <p class="text-xl font-bold text-gray-500 line-through">₹<?php echo $row['max_price']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php if ($row['status'] == 1): ?>
                            <div class="bg-red-50 text-red-800 text-center py-6 rounded-xl border-2 border-red-100">
                                <h2 class="text-2xl font-black">🎉 SOLD!</h2>
                                <p class="mt-1">Congratulations to the winner:</p>
                                <p class="text-xl font-bold mt-1 text-red-600"><?php echo htmlspecialchars($row['bidder_name']); ?></p>
                            </div>

                        <?php elseif ($row['status'] == 2): ?>
                            <div class="bg-gray-200 text-gray-600 text-center py-4 rounded-lg font-bold">
                                🚫 Auction Expired (No Bids)
                            </div>

                        <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['seller_id']): ?>
                            <div class="bg-yellow-50 text-yellow-800 text-center py-4 rounded-lg font-mono border border-yellow-200">
                                This is your listing. <br><span class="text-xs">Wait for students to bid!</span>
                            </div>

                        <?php else: ?>
                            <a href="buy_now.php?id=<?php echo $row['id']; ?>" 
                               class="block w-full text-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-4 text-xl font-bold rounded-lg shadow-lg transform transition hover:-translate-y-1">
                                ⚡ BUY NOW for ₹<?php echo $row['max_price']; ?>
                            </a>
                            <p class="text-center text-xs text-gray-400 mt-2">Instant purchase, skips bidding war.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
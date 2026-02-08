<?php 
include 'includes/db.php'; 

date_default_timezone_set('Asia/Kolkata'); 

$sql_sold = "UPDATE products 
             SET status = 1 
             WHERE created_at <= (NOW() - INTERVAL 1 HOUR) 
             AND status = 0 
             AND last_bidder_id IS NOT NULL";
mysqli_query($conn, $sql_sold);


$sql_expired = "UPDATE products 
                SET status = 2 
                WHERE created_at <= (NOW() - INTERVAL 1 HOUR) 
                AND status = 0 
                AND last_bidder_id IS NULL";
mysqli_query($conn, $sql_expired);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Cart - Live Auction</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <nav class="bg-blue-900 p-4 text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold flex items-center gap-2">🛒 Campus Cart</a>
            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="hidden md:inline text-gray-300 text-sm">Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?></span>
                    <a href="sell.php" class="bg-yellow-500 hover:bg-yellow-400 text-black px-4 py-2 rounded-lg font-bold transition shadow-sm">+ Sell</a>
                    <a href="logout.php" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-2 rounded text-white transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="bg-white text-blue-900 hover:bg-gray-200 px-5 py-2 rounded-lg font-bold transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4 flex-grow">
        
        <div class="flex justify-between items-end mb-6 border-b border-gray-300 pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Live Auctions</h1>
                <p class="text-gray-500 text-sm mt-1">Items auto-sell after 1 Hour! ⏳</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">Live Bidding Active</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $sql = "SELECT p.*, u.name as seller_name, b.name as bidder_name 
                    FROM products p 
                    JOIN users u ON p.seller_id = u.id 
                    LEFT JOIN users b ON p.last_bidder_id = b.id 
                    ORDER BY p.created_at DESC";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    $created_time = strtotime($row['created_at']);
                    $end_time = $created_time + 3600; 
                    $time_left = $end_time - time();
                    
                    if ($row['status'] == 1) {
                        $badge = "SOLD TO " . strtoupper(explode(' ', $row['bidder_name'])[0]) . " 🎉";
                        $badge_bg = "bg-red-600";
                        $border_class = "border-red-500 border-4";
                    } elseif ($row['status'] == 2) {
                        $badge = "EXPIRED (NO BIDS) 🚫";
                        $badge_bg = "bg-gray-600";
                        $border_class = "border-gray-400 border-4";
                    } else {
                        $badge = "ACTIVE 🟢";
                        $badge_bg = "bg-green-600";
                        $border_class = "border-green-500 border-4";
                    }
            ?>
                    <div class="bg-white rounded-xl shadow-lg <?php echo $border_class; ?> overflow-hidden flex flex-col relative transition hover:scale-[1.02]">
                        
                        <div class="absolute top-0 left-0 <?php echo $badge_bg; ?> text-white text-[10px] font-bold px-3 py-1 rounded-br-lg z-10 shadow-md">
                            <?php echo $badge; ?>
                        </div>

                        <div class="h-48 overflow-hidden bg-gray-100">
                            <img src="uploads/<?php echo $row['image']; ?>" class="w-full h-full object-cover">
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo htmlspecialchars($row['title']); ?></h3>
                            
                            <div class="flex justify-between items-center mt-2 bg-gray-50 p-2 rounded">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase">Current Bid</p>
                                    <p class="text-xl font-black text-blue-900">₹<?php echo $row['price']; ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase">Buy Now</p>
                                    <p class="text-sm font-bold text-gray-400 line-through">₹<?php echo $row['max_price']; ?></p>
                                </div>
                            </div>

                            <div class="mt-2 h-5 text-center">
                                <?php if ($row['status'] == 1): ?>
                                    <p class="text-xs text-red-600 font-bold">👑 Winner: <?php echo htmlspecialchars($row['bidder_name']); ?></p>
                                <?php elseif ($row['bidder_name']): ?>
                                    <p class="text-xs text-purple-700 font-bold">🔥 Top Bidder: <?php echo htmlspecialchars($row['bidder_name']); ?></p>
                                <?php else: ?>
                                    <p class="text-xs text-gray-400 italic">No bids yet.</p>
                                <?php endif; ?>
                            </div>

                            <?php if ($row['status'] == 0): ?>
                                <p class="text-center text-red-600 text-xs font-bold mt-2">
                                    ⏳ Ends in: <?php echo round($time_left / 60); ?> mins
                                </p>
                            <?php endif; ?>

                            <div class="mt-4 space-y-2">
                                
                                <?php if ($row['status'] == 0 && isset($_SESSION['user_id']) && $_SESSION['user_id'] != $row['seller_id']): ?>
                                    
                                    <a href="buy_now.php?id=<?php echo $row['id']; ?>" class="block w-full text-center bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-1 rounded text-sm shadow-sm">
                                        ⚡ Buy Now @ ₹<?php echo $row['max_price']; ?>
                                    </a>

                                    <form action="update_bid.php" method="POST" class="grid grid-cols-3 gap-1">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="bid_amount" value="1" class="bg-blue-50 border border-blue-300 text-blue-700 hover:bg-blue-600 hover:text-white text-xs font-bold py-1 rounded">+₹1</button>
                                        <button type="submit" name="bid_amount" value="5" class="bg-blue-50 border border-blue-300 text-blue-700 hover:bg-blue-600 hover:text-white text-xs font-bold py-1 rounded">+₹5</button>
                                        <button type="submit" name="bid_amount" value="10" class="bg-blue-50 border border-blue-300 text-blue-700 hover:bg-blue-600 hover:text-white text-xs font-bold py-1 rounded">+₹10</button>
                                    </form>

                                <?php endif; ?>

                                <a href="view_product.php?id=<?php echo $row['id']; ?>" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 rounded text-sm">
                                    <?php echo ($row['status'] == 1) ? "🏆 See Winner Details" : "📄 View Details"; ?>
                                </a>

                                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['seller_id']): ?>
                                    <div class="text-center text-[10px] text-gray-500 font-mono mt-1 border-t pt-1">You own this item</div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-span-full text-center py-20 text-gray-500 font-bold'>No items found. Start selling now!</div>";
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
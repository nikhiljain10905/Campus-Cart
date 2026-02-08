<?php
include 'config.php';
$apiKey = GEMINI_API_KEY;
$model = "gemini-3-flash-preview";

header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!isset($input['title']) || !isset($input['desc'])) {
    echo json_encode(["error" => "Missing title or description"]);
    exit();
}

$prompt = "Generate 5 relevant hashtags for this item: 
Title: {$input['title']}
Description: {$input['desc']}
Output format: #tag1, #tag2, #tag3. No intro text.";

$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey;

$data = [
    "contents" => [
        ["parts" => [["text" => $prompt]]]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(["error" => "Connection Error: " . curl_error($ch)]);
} else {
    $decoded = json_decode($response, true);
    
    if ($httpCode === 200) {
        $aiText = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? "";
        echo json_encode(["tags" => trim($aiText)]);
    } else {
        $errorMsg = $decoded['error']['message'] ?? "Unknown Error";
        echo json_encode(["error" => "API Error ($httpCode): $errorMsg"]);
    }
}

curl_close($ch);
?>
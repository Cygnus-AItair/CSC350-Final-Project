<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> AI Chat Website</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f7f7f7; }
        textarea { width: 100%; height: 100px; }
        .response { background: white; padding: 15px; border-radius: 10px; margin-top: 10px; }
    </style>
</head>
<body>

<h1> Talk to AI </h1>

<form method="post">
    <textarea name ="user_input" placeholder="Ask me anything...."required</textarea><br><br>
    <button type="submit">Send</button>
</form>


<?php
if ($_SERVER["REQUEST METHOD"] === "POST") {
    $user_input = htmlspecialchars($_POST["user_input"]);

    // Replace with your API key 
    $apiKey = "Your_AI_API_KEY";
    $url = "https://api.openai.com/v1/chat/completions";

    $data = [
        "model" => "opt-3.5-turbo",
        "messages" => [
             ["role" => "user", "content" => $user_input]
         ]
    ];

   $options = [
       "http => [
            "header" => "Content-Type: application/json\r\n" .
                        "Authorization: Bearer $apiKey\r\n",
            "method" => "POST",
            "content" => json_encode($data).
    ],
 ];

 $context = stream_context_create($options);
 $response = file_get_contents($url, false, $context);
 $result = json_decode($response, true);

 $ai_reply = $result["choices"][0]["message"]["content"] ?? "Error getting response.";
 
 echo "<div class='response'>strong>AI:</strong> $ai_reply</div>";
}
?>


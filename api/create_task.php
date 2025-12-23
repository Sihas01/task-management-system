<?php

require '../config/db.php';

//take data from POST request
$title = $_POST['title'];
$description = $_POST['description'];
$status = "pending";

$query = "INSERT INTO tasks (title, description, status) 
          VALUES (?, ?, ?)";

$stmt = $pdo->prepare($query);

//execute the query
$stmt->execute([$title, $description, $status]);

echo json_encode(['status' => 'success']);

?>

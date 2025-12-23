<?php

header('Content-Type: application/json');

require '../config/db.php';

try {

    $query = "SELECT * FROM tasks";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $tasks
    ]);

}catch (PDOException $e) {
    //datanase error handling

    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;

} catch (Exception $e) {
    //other errors handling
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ]);
    }


?>
<?php

header('Content-Type: application/json');

require '../config/db.php';

try{
    //take data from POST request
    //check user send data or not
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';


    //set default status as pending
    $status = "pending";

    //validate data
    $errors = [];
    if (empty($title)) {
        $errors[] = 'Title is required';
    }

    if (empty($description)) {
        $errors[] = 'Description is required';
    }

    if (!empty($errors)) {
        echo json_encode(['status'=>'error','errors'=>$errors]);
        exit;
    }

    $query = "INSERT INTO tasks (title, description, status) 
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($query);

    //execute the query
    $stmt->execute([$title, $description, $status]);

    echo json_encode([
            'status' => 'success',
            'message' => 'Task created successfully',
            'task_id' => $pdo->lastInsertId()
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
    exit;
}
?>

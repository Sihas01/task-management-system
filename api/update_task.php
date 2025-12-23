<?php

header('Content-Type: application/json');

require '../config/db.php';

try{


    //take data from POST request
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';


    //validate data
    $errors = [];
    if (empty($id)) {
        $errors[] = 'Task ID is required';
    }

    if (empty($title)) {
        $errors[] = 'Title is required';
    }

    if (empty($description)) {
        $errors[] = 'Description is required';
    }

    if (empty($status)) {
        $errors[] = 'Status is required';
    }

    if (!empty($errors)) {
        echo json_encode(['status'=>'error','errors'=>$errors]);
        exit;
    }

    // Check if task exists
    $checkStmt = $pdo->prepare("SELECT id FROM tasks WHERE id = ?");
    $checkStmt->execute([$id]);

    if ($checkStmt->rowCount() === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Task not found'
        ]);
        exit;
    }

    $query = "UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$title, $description, $status, $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Task updated successfully'
    ]);

}catch (PDOException $e) {
    //datanase error handling

    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);

} catch (Exception $e) {
    //other errors handling

    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ]);
    
}
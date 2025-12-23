<?php

header('Content-Type: application/json');

require '../config/db.php';

try{

    //take id and status from POST request
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    //validate data
    $errors = [];   

    if (empty($id)) {
        $errors[] = 'Task ID is required';
    }

    if (empty($status)) {
        $errors[] = 'Status is required';
    }elseif ($status !== 'Pending' && $status !== 'Completed' && $status !== 'In Progress'){ //validating status value
        $errors[] = 'Invalid status value';
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


    $query = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$status, $id]);


    echo json_encode([
        'status' => 'success',
        'message' => 'Task status updated successfully'
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

?>
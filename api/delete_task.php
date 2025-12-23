<?php 

header('Content-Type: application/json');

require '../config/db.php';

try {

    //take task id 
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';

    //validate data
    $errors = [];
    if (empty($id)) {
        $errors[] = 'Task ID is required';
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

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Task deleted successfully'
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
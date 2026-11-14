<?php
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // 1. ดึงรายการแชททั้งหมดของฟรีแลนซ์ (Inbox)
    if (isset($_GET['freelance_inbox'])) {
        $fid = intval($_GET['freelance_inbox']);
        
        $sql = "SELECT c.service_id, c.customer_id, 
                       s.title AS service_title, 
                       u.fullname AS customer_name,
                       (SELECT message FROM chats WHERE service_id = c.service_id AND customer_id = c.customer_id ORDER BY id DESC LIMIT 1) AS last_message
                FROM chats c
                JOIN services s ON c.service_id = s.id
                JOIN users u ON c.customer_id = u.id
                WHERE s.freelance_id = $fid
                GROUP BY c.service_id, c.customer_id
                ORDER BY MAX(c.id) DESC";

        $result = $conn->query($sql);
        $inbox = [];
        while ($row = $result->fetch_assoc()) {
            $inbox[] = $row;
        }
        echo json_encode($inbox);
        exit();
    }

    // 2. ดึงข้อความแชทเฉพาะคู่สนทนานั้นๆ (ระหว่าง service_id กับ customer_id)
    if (isset($_GET['service_id']) && isset($_GET['customer_id'])) {
        $service_id = intval($_GET['service_id']);
        $customer_id = intval($_GET['customer_id']);
        
        $sql = "SELECT c.*, u.fullname AS sender_name 
                FROM chats c 
                JOIN users u ON c.sender_id = u.id 
                WHERE c.service_id = $service_id AND c.customer_id = $customer_id 
                ORDER BY c.id ASC";

        $result = $conn->query($sql);
        $chats = [];
        while ($row = $result->fetch_assoc()) {
            $chats[] = $row;
        }
        echo json_encode($chats);
        exit();
    }
}

// 3. บันทึกข้อความแชทใหม่
elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($data->service_id) && isset($data->customer_id) && isset($data->sender_id) && isset($data->message)) {
        $service_id = intval($data->service_id);
        $customer_id = intval($data->customer_id);
        $sender_id = intval($data->sender_id);
        $message = $conn->real_escape_string($data->message);
        
        $sql = "INSERT INTO chats (service_id, customer_id, sender_id, message) VALUES ($service_id, $customer_id, $sender_id, '$message')";
        if ($conn->query($sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    }
}
?>
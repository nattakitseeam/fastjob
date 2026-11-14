<?php
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    // 1. ดึงรายการห้องแชททั้งหมด (Inbox) ใช้ได้ทั้งลูกค้าและฟรีแลนซ์
    if ($action === 'inbox') {
        $user_id = intval($_GET['user_id'] ?? 0);
        $role = $_GET['role'] ?? '';

        if ($user_id <= 0) {
            echo json_encode([]);
            exit();
        }

        if ($role === 'freelance') {
            $sql = "SELECT 
                        c.service_id,
                        c.customer_id,
                        MAX(s.title) AS service_title,
                        MAX(u_cust.fullname) AS partner_name,
                        (SELECT message FROM chats c2 WHERE c2.service_id = c.service_id AND c2.customer_id = c.customer_id ORDER BY c2.id DESC LIMIT 1) AS last_message,
                        MAX(c.created_at) AS last_time,
                        MAX(c.id) AS last_id
                    FROM chats c
                    INNER JOIN services s ON c.service_id = s.id
                    INNER JOIN users u_cust ON c.customer_id = u_cust.id
                    WHERE s.freelance_id = ? AND c.customer_id > 0
                    GROUP BY c.service_id, c.customer_id
                    ORDER BY last_id DESC";
        } else {
            $sql = "SELECT 
                        c.service_id,
                        c.customer_id,
                        MAX(s.title) AS service_title,
                        MAX(u_free.fullname) AS partner_name,
                        (SELECT message FROM chats c2 WHERE c2.service_id = c.service_id AND c2.customer_id = c.customer_id ORDER BY c2.id DESC LIMIT 1) AS last_message,
                        MAX(c.created_at) AS last_time,
                        MAX(c.id) AS last_id
                    FROM chats c
                    INNER JOIN services s ON c.service_id = s.id
                    INNER JOIN users u_free ON s.freelance_id = u_free.id
                    WHERE c.customer_id = ? AND c.service_id > 0
                    GROUP BY c.service_id, c.customer_id
                    ORDER BY last_id DESC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $inbox = [];
        while ($row = $result->fetch_assoc()) {
            $inbox[] = $row;
        }
        echo json_encode($inbox);
        exit();
    }

    // 2. ดึงรายละเอียดห้องแชท + ประวัติข้อความทั้งหมดในห้องนั้น
    if ($action === 'messages') {
        $service_id = intval($_GET['service_id'] ?? 0);
        $customer_id = intval($_GET['customer_id'] ?? 0);

        if ($service_id <= 0 || $customer_id <= 0) {
            echo json_encode(["room" => null, "messages" => []]);
            exit();
        }

        // ดึงข้อมูลหัวห้องแชท (ชื่องาน, ชื่อฟรีแลนซ์, ชื่อลูกค้า)
        $roomSql = "SELECT 
                        s.id AS service_id,
                        s.title AS service_title,
                        s.price AS service_price,
                        s.freelance_id,
                        u_free.fullname AS freelance_name,
                        u_cust.fullname AS customer_name
                    FROM services s
                    INNER JOIN users u_free ON s.freelance_id = u_free.id
                    INNER JOIN users u_cust ON u_cust.id = ?
                    WHERE s.id = ?";
        $roomStmt = $conn->prepare($roomSql);
        $roomStmt->bind_param("ii", $customer_id, $service_id);
        $roomStmt->execute();
        $roomInfo = $roomStmt->get_result()->fetch_assoc();

        // ดึงข้อความแชททั้งหมด
        $msgSql = "SELECT c.id, c.service_id, c.customer_id, c.sender_id, c.message, c.created_at, u.fullname AS sender_name
                   FROM chats c
                   INNER JOIN users u ON c.sender_id = u.id
                   WHERE c.service_id = ? AND c.customer_id = ?
                   ORDER BY c.id ASC";
        $msgStmt = $conn->prepare($msgSql);
        $msgStmt->bind_param("ii", $service_id, $customer_id);
        $msgStmt->execute();
        $msgResult = $msgStmt->get_result();

        $messages = [];
        while ($row = $msgResult->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode([
            "room" => $roomInfo,
            "messages" => $messages
        ]);
        exit();
    }
}

// 3. บันทึกข้อความแชทใหม่
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $service_id = intval($data['service_id'] ?? 0);
    $customer_id = intval($data['customer_id'] ?? 0);
    $sender_id = intval($data['sender_id'] ?? 0);
    $message = trim($data['message'] ?? '');

    if ($service_id <= 0 || $customer_id <= 0 || $sender_id <= 0 || $message === '') {
        echo json_encode(["status" => "error", "message" => "ข้อมูลห้องแชทหรือข้อความไม่ครบถ้วน"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO chats (service_id, customer_id, sender_id, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $service_id, $customer_id, $sender_id, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit();
}
?>
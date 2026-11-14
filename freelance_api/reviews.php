<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db.php'; // เปลี่ยนชื่อไฟล์เชื่อมต่อ DB ให้ตรงกับที่ใช้ใน services.php (เช่น config.php หรือ db.php)

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // ดึงรีวิวตาม service_id หรือ freelance_id หรือดึงสรุปคะแนนทั้งหมด
    if (isset($_GET['service_id'])) {
        $service_id = intval($_GET['service_id']);
        $stmt = $conn->prepare("
            SELECT r.*, u.fullname AS customer_name 
            FROM reviews r 
            LEFT JOIN users u ON r.customer_id = u.id 
            WHERE r.service_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        echo json_encode($reviews);
        exit;
    }

    if (isset($_GET['freelance_id'])) {
        $freelance_id = intval($_GET['freelance_id']);
        $stmt = $conn->prepare("
            SELECT r.*, u.fullname AS customer_name, s.title AS service_title 
            FROM reviews r 
            LEFT JOIN users u ON r.customer_id = u.id 
            LEFT JOIN services s ON r.service_id = s.id
            WHERE r.freelance_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $freelance_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        echo json_encode($reviews);
        exit;
    }

    // สรุปคะแนนเฉลี่ยและจำนวนรีวิวแยกตาม service_id (สำหรับแสดงบนการ์ดหน้าแรก)
    $sql = "SELECT service_id, ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS total_reviews FROM reviews GROUP BY service_id";
    $result = $conn->query($sql);
    $summary = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $summary[$row['service_id']] = [
                'avg_rating' => floatval($row['avg_rating']),
                'total_reviews' => intval($row['total_reviews'])
            ];
        }
    }
    echo json_encode($summary);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $service_id = intval($data['service_id'] ?? 0);
    $freelance_id = intval($data['freelance_id'] ?? 0);
    $customer_id = intval($data['customer_id'] ?? 0);
    $rating = intval($data['rating'] ?? 5);
    $comment = trim($data['comment'] ?? '');

    if ($service_id <= 0 || $customer_id <= 0 || $comment === '') {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลรีวิวให้ครบถ้วน']);
        exit;
    }

    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    // หา freelance_id จากตาราง services หากไม่ได้ส่งมา
    if ($freelance_id <= 0) {
        $sStmt = $conn->prepare("SELECT freelance_id FROM services WHERE id = ?");
        $sStmt->bind_param("i", $service_id);
        $sStmt->execute();
        $sRes = $sStmt->get_result()->fetch_assoc();
        $freelance_id = intval($sRes['freelance_id'] ?? 0);
    }

    $stmt = $conn->prepare("INSERT INTO reviews (service_id, freelance_id, customer_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $service_id, $freelance_id, $customer_id, $rating, $comment);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'ส่งรีวิวเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกรีวิวได้']);
    }
    exit;
}
?>
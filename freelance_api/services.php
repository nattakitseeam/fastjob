<?php
include 'db.php';
$method =$_SERVER['REQUEST_METHOD'];

// 1. ดึงข้อมูลบริการทั้งหมด หรือเฉพาะของฟรีแลนซ์
if ($method == 'GET') {$where = "";
    if (isset($_GET['freelance_id'])) {$fid = intval($_GET['freelance_id']);$where = "WHERE s.freelance_id = $fid";
    } elseif (isset($_GET['public'])) {$where = "WHERE s.status = 'อนุมัติ'";
    }

    $sql = "SELECT s.*, u.fullname AS freelance_name 
            FROM services s 
            JOIN users u ON s.freelance_id = u.id 
            $where 
            ORDER BY s.id DESC";
            
    $result =$conn->query($sql);$services = [];
    while ($row =$result->fetch_assoc()) {
        if (!empty($row['image_path'])) {
            $row['image_url'] = "http://localhost/fastjob/freelance_api/uploads/" . $row['image_path'];
        } else {
            $row['image_url'] = null;
        }
        $services[] =$row;
    }
    echo json_encode($services);
}

// 2. สร้างบริการใหม่ + อัปโหลดรูปภาพ
elseif ($method == 'POST') {
    $freelance_id = intval($_POST['freelance_id']);
    $title =$conn->real_escape_string($_POST['title']);$category = $conn->real_escape_string($_POST['category']);
    $description =$conn->real_escape_string($_POST['description']);$price = floatval($_POST['price']);$image_path = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {$upload_dir = __DIR__ . "/uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . "_" . uniqid() . "." . $ext;
        $target_file = $upload_dir .$filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'],$target_file)) {
            $image_path =$filename;
        }
    }

    $img_sql = $image_path ? "'$image_path'" : "NULL";
    $sql = "INSERT INTO services (freelance_id, title, category, description, price, image_path) 
            VALUES ($freelance_id, '$title', '$category', '$description', $price,$img_sql)";
            
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}

// 3. อัปเดตสถานะอนุมัติ / ไม่อนุมัติ (สำหรับ Admin)
elseif ($method == 'PUT') {$data = json_decode(file_get_contents("php://input"));
    $id = intval($data->id);$status = $conn->real_escape_string($data->status);
    
    if ($conn->query("UPDATE services SET status='$status' WHERE id=$id")) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}

// 4. ลบรายการบริการ
elseif ($method == 'DELETE') {
    $id = intval($_GET['id']);
    
    $res =$conn->query("SELECT image_path FROM services WHERE id=$id");
    if ($row =$res->fetch_assoc()) {
        if (!empty($row['image_path']) && file_exists(__DIR__ . "/uploads/" . $row['image_path'])) {
            unlink(__DIR__ . "/uploads/" . $row['image_path']);
        }
    }

    if ($conn->query("DELETE FROM services WHERE id=$id")) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
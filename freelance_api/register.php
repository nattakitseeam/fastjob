<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->password) && isset($data->fullname) && isset($data->role)) {
    $user =$conn->real_escape_string($data->username);$pass = $conn->real_escape_string($data->password);
    $name =$conn->real_escape_string($data->fullname);$role = $conn->real_escape_string($data->role);

    $check =$conn->query("SELECT id FROM users WHERE username='$user'");
    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username นี้มีผู้ใช้งานแล้ว"]);
        exit();
    }

    $sql = "INSERT INTO users (username, password, fullname, role) VALUES ('$user', '$pass', '$name', '$role')";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ"]);
    } else {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $conn->error]);
    }
}
?>
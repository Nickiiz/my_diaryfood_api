<?php
//เป็น API ที่ใช้สำหรับ รับ request ข้อมูลที่ลบออกจาตาราง diaryfood_tb และ reponse ข้อมูลกลับไปเมื่อลบเรียบร้อยแล้ว

//กำหนด header เพื่อมีการเรียกใช้งานข้ามโดเมน
header("Access-Control-Allow-Origin: *");
//กำหนด header เพื่อกำหนดรูปแบบข้อมูลในการตอบกลับแบบ JSON ตาม Concept ของ RestAPI/RestFullAPI
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include_once คือ เรียกใช้งานไฟล์
include_once "./../../connectdb.php"; 
include_once "./../../models/diaryfood.php";

//สร้างออปเจ็ตก์เพื่อทำงานกับฐานข้อมูล ในที่นี้คือ mydiaryfood_db นั่นเอง
$database = new ConnectDB();
$db = $database->getConnectionDB();

//สร้างออปเจ็กต์เพื่อทำงานกับตาราง diaryfood_tb
$diaryfood = new Diaryfood($db );

//สร้างตัวแปรเก็บค่าข้อมูลที่ส่งมาจากการเรียกใช้ api
$data = json_decode(file_get_contents("php://input"));

//กำหนดแต่ละข้อมูลที่ส่งมาจากการเรียกใช้ API ให้กับออปเจ็กต์ $diaryfood 
$diaryfood->foodId = $data->foodId;

//เรียกใช้งานฟังก์ชัน delete_diaryfood()
if ($diaryfood->delete_diaryfood()) {
    //กำหนด response code - 200 OK
    http_response_code(200);

    //กำหนดรูปแบบข้อมูลเป็น json โดยกำหนด message เป็น 1 เพื่อบอกว่าลบข้อมูลเรียบร้อยแล้ว
    echo json_encode(array("message" => "1"));
} else {
    //กำหนด response code - 200 OK
    http_response_code(503);

    //กำหนดรูปแบบข้อมูลเป็น json โดยกำหนด message เป็น 2 เพื่อบอกว่าการลบข้อมูลไม่สำเร็จ
    echo json_encode(array("message" => "2"));
}


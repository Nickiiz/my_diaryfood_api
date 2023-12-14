<?php
//เป็น API ที่ใช้สำหรับ รับ request และส่งกลับ response ข้อมูลทั้งหมดจากตาราง diaryfood_tb กลับไป

//กำหนด header เพื่อมีการเรียกใช้งานข้ามโดเมน
header("Access-Control-Allow-Origin: *");
//กำหนด header เพื่อกำหนดรูปแบบข้อมูลในการตอบกลับแบบ JSON ตาม Concept ของ RestAPI/RestFullAPI
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include_once คือ เรียกใช้งานไฟล์
include_once "./../connectdb.php"; 
include_once "./../models/member.php";
//สร้างออปเจ็ตก์เพื่อทำงานกับฐานข้อมูล ในที่นี้คือ mydiaryfood_db นั่นเอง
$database = new ConnectDB();
$db = $database->getConnectionDB();

//สร้างออปเจ็กต์เพื่อทำงานกับตาราง member_tb
$member = new Member($db );

//สร้างตัวแปรเก็บค่าข้อมูลที่ส่งมาจากการเรียกใช้ api
$data = json_decode(file_get_contents("php://input"));

//กำหนดแต่ละข้อมูลที่ส่งมาจากการเรียกใช้ API ให้กับออปเจ็กต์ $member
$member->memUsername = $data->memUsername;
$member->memPassword = $data->memPassword ;

//สร้างตัวแปรเก็บผลลัพธ์ที่ได้จากการเรียกใช้ฟังก์ชัน checkLogin()
$stmt = $member->checkLogin();

//สร้างตัวแปรเก็บจำนวนเรคอร์ด/แถว/ชุดข้อมูลจากผลที่เก็บในตัวแปร $stmt
$numrow = $stmt->rowCount();

//ตรวจสอบว่ามีข้อมูลหรือไม่
if( $numrow > 0 ){ 
    http_response_code(200);
    
    echo json_encode(array("message" => "1")); 
}else{
    //ไม่มีข้อมูล
    http_response_code(200);
   
    echo json_encode(array("message" => "0")); 
}


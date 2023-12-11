<?php
//เป็น API ที่ใช้สำหรับ รับ request และส่งกลับ response ข้อมูลทั้งหมดจากตาราง diaryfood_tb กลับไป

//กำหนด header เพื่อมีการเรียกใช้งานข้ามโดเมน
header("Access-Control-Allow-Origin: *");
//กำหนด header เพื่อกำหนดรูปแบบข้อมูลในการตอบกลับแบบ JSON ตาม Concept ของ RestAPI/RestFullAPI
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include_once คือ เรียกใช้งานไฟล์
include_once "./../../connectdb.php"; 
include_once "./../../models/diaryfood.php";
//สร้างออปเจ็ตก์เพื่อทำงานกับฐานข้อมูล ในที่นี้คือ mydiaryfood_db นั่นเอง
$database = new ConnectDB();
$db = $database->getConnectionDB();

//สร้างออปเจ็กต์เพื่อทำงานกับตาราง diaryfood_tb
$diaryfood = new Diaryfood($db );

//สร้างตัวแปรเก็บผลลัพธ์ที่ได้จากการเรียกใช้ฟังก์ชัน getall_diaryfood()
$stmt = $diaryfood->getall_diaryfood();

//สร้างตัวแปรเก็บจำนวนเรคอร์ด/แถว/ชุดข้อมูลจากผลที่เก็บในตัวแปร $stmt
$numrow = $stmt->rowCount();

//ตรวจสอบว่ามีข้อมูลหรือไม่
if( $numrow > 0 ){
    //มีข้อมูล
    //สร้างตัวแปรอาร์เรย์เพื่อเก็บข้อมูลที่อยู่ใน $stmt เพื่อที่จะ response กลับไป
    $diaryfood_arr = array();

    //วนลูปเอาตัวแปรใน $stmt เก็บในอาร์เรย์
    while($rec = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($rec);
        $diaryfood_item = array(
            "message" => "1",
            "foodId" => $foodId,
            "foodShopname" => $foodShopname,
            "foodImage" => $foodImage,
            "foodPay" => $foodPay,
            "foodMeal" => $foodMeal,
            "foodDate" => $foodDate,
            "foodLat" => $foodLat,
            "foodLng" => $foodLng,
            "foodProvince" => $foodProvince,
        );
        array_push($diaryfood_arr, $diaryfood_item);
    }
    
    http_response_code(200);
    //เอาข้อมูลในอาร์เรย์มาทำเป็นรูปแบบ JSON เพื่อส่งกลับไป
    echo json_encode($diaryfood_arr); 
}else{
    //ไม่มีข้อมูล
    http_response_code(200);
     //กำหนดรูปแบบข้อมูลเป็น json โดยกำหนด message เป็น 0 เพื่อบอกว่าไม่มีข้อมูลในฐานข้อมูล
    echo json_encode(array("message" => "0")); 
}


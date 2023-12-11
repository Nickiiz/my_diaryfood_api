<?php
//เป็น API ที่ใช้สำหรับ รับ request ข้อมูลที่จะแก้ไขในตาราง diaryfood_tb และ reponse ข้อมูลกลับไปเมื่อแก้ไขเรียบร้อยแล้ว

//กำหนด header เพื่อมีการเรียกใช้งานข้ามโดเมน
header("Access-Control-Allow-Origin: *");
//กำหนด header เพื่อกำหนดรูปแบบข้อมูลในการตอบกลับแบบ JSON ตาม Concept ของ RestAPI/RestFullAPI
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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
$diaryfood->foodShopname = $data->foodShopname;
$diaryfood->foodPay = $data->foodPay ;
$diaryfood->foodMeal = $data->foodMeal;
$diaryfood->foodDate = $data->foodDate;
$diaryfood->foodProvince = $data->foodProvince;

//สำหรับรูปภาพที่อัปโหลดมา
//ตรวจสอบว่ามีการอัปโหลดรูปมาเพื่อที่จะแก้ไขรูปในตารางหรือไม่
if( strlen($data->foodImage) != 0) {
    //กรณีมีการอัปโหลดรูปเพื่อแก้ไข
    //สร้างตัวแปรเก็บรูปภาพที่ได้มาจากการอัปโหลด ซึ่งตัวรูปจะอยู่ในรูปแบบของ base64
    $picture_temp = $data->foodImage;
    //กำหนดชื่อไฟล์รูปให้กับออปเจ็กต์ $diaryfood เพื่อจะใช้บันทึกในตาราง diaryfood_tb
    $diaryfood->foodImage = "pic". round(microtime(true) * 1000). ".jpg";
    //นำรูปภาพที่อยู่ในตัวแปรซึ่งเป็น base64 แปลงเป็นรูปภาพและนำไปวางไว้ที่ไดเรกทอรี่ที่กำหนด
    //โดยกำหนดชื่อไฟล์รูปตามที่กำหนดไว้กับออปเจ็กต์ $diaryfood ข้างต้น
    file_put_contents("./../../images/". $diaryfood->foodImage, base64_decode($picture_temp));
}else{
    //กรณีไม่มีการอัปโหลดรูปเพื่อแก้ไข
    //กำหนดชื่อไฟล์รูปเป็นค่าว่าง เพื่อใช้ตรวจสอบว่าไม่มีการอัปโหลดรูปเพื่อแก้ไข
    $diaryfood->foodImage = "";
}

//เรียกใช้งานฟังก์ชัน update_diaryfood()
if ($diaryfood->update_diaryfood()) {
    //กำหนด response code - 200 OK
    http_response_code(200);

    //กำหนดรูปแบบข้อมูลเป็น json โดยกำหนด message เป็น 1 เพื่อบอกว่าแก้ไขข้อมูลเรียบร้อยแล้ว
    echo json_encode(array("message" => "1"));
} else {
    //กำหนด response code - 200 OK
    http_response_code(503);

    //กำหนดรูปแบบข้อมูลเป็น json โดยกำหนด message เป็น 2 เพื่อบอกว่าการแก้ไขข้อมูลไม่สำเร็จ
    echo json_encode(array("message" => "2"));
}


<?php
//ไฟล์นี้เป็นไฟล์ที่จะทำงานกับตาราง diaryfood_tb ในฐานข้อมูล
//ทั้งการ INSERT/UPDATE/DELETE/SELECT

class Diaryfood
{
    //สร้างตัวแปรที่ใช้เป็น connection ทำงานกับ ฐานข้อมูล
    private $conn;

    //สร้างตัวแปรที่จะทำงานกับข้อมูล(field)ของตาราง diaryfood_tb
    public $foodId;
    public $foodShopname;
    public $foodImage;
    public $foodPay;
    public $foodMeal;
    public $foodDate;
    public $foodLat;
    public $foodLng;
    public $foodProvince;
    //สร้างตัวแปรตัวแปรนี้เอาไว้ใช้สำหรับข้อมูลอื่นๆ
    public $message;

    //สร้างคอนสตรักเตอร์ เพื่อกำหนดการเชื่อมต่อฐานข้อมูลที่จะทำงานด้วย
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //สร้างฟังก์ชันเพื่อดึงข้อมูลทั้งหมดจากตาราง diaryfood_tb 
    //ใช้คู่กับไฟล์ api_getall_diaryfood.php
    public function getall_diaryfood()
    {
        //สร้างตัวแปรเก็บคำสั่ง SQL
        $strSql = "SELECT * FROM diaryfood_tb ORDER BY foodId DESC";

        //สรัางตัวแปรเพื่อจัดการกับคำสั่ง SQL (เป็นการเตรียมคำสั่ง SQL ให้พร้อมทำงาน)
        $stmt = $this->conn->prepare($strSql);

        //สั่งให้ SQL ทำงานผ่านตัวแปรที่จัดการกับคำสั่ง SQL
        $stmt->execute();

        //ส่งผลที่ได้กลับไปที่จุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }

    //---------------------------------------------------------
    //สร้างฟังก์ชันเพิ่มข้อมูลลงในตาราง diaryfood_tb 
    //ใช้คู่กับไฟล์ api_insert_diaryfood.php
    public function insert_diaryfood()
    {
        //สร้างตัวแปรและกำหนดค่าเป็นคำสั่ง SQL เพิ่มข้อมูลลงในตาราง diaryfood_tb
        $strSql = "INSERT INTO diaryfood_tb 
                    (foodShopname, foodImage, foodPay, foodMeal, foodDate, foodLat, foodLng, foodProvince) 
                  VALUE 
                    (:foodShopname, :foodImage, :foodPay, :foodMeal, :foodDate, :foodLat, :foodLng, :foodProvince)";

        //sanitize ทำความสะอาดข้อมูล เพื่อกรองข้อมูลป้องกัน SQL injection ก่อนที่จะนำข้อมูลลงในตาราง
        $this->foodShopname = htmlspecialchars(strip_tags($this->foodShopname));
        $this->foodImage = htmlspecialchars(strip_tags($this->foodImage));
        $this->foodPay = htmlspecialchars(strip_tags($this->foodPay));
        $this->foodMeal = intval(htmlspecialchars(strip_tags($this->foodMeal)));
        $this->foodDate = htmlspecialchars(strip_tags($this->foodDate));
        $this->foodLat = doubleval(htmlspecialchars(strip_tags($this->foodLat)));
        $this->foodLng = doubleval(htmlspecialchars(strip_tags($this->foodLng)));
        $this->foodProvince = htmlspecialchars(strip_tags($this->foodProvince));

        //สร้างตัวแปรเพื่อจัดการกับคำสั่ง SQL 
        $stmt = $this->conn->prepare($strSql);

        //bind values เพื่อกำหนดข้อมูลให้กับ parameter ที่กำหนดไว้ที่คำสั่ง SQL
        $stmt->bindParam(":foodShopname", $this->foodShopname);
        $stmt->bindParam(":foodImage", $this->foodImage);
        $stmt->bindParam(":foodPay", $this->foodPay);
        $stmt->bindParam(":foodMeal", $this->foodMeal);
        $stmt->bindParam(":foodDate", $this->foodDate);
        $stmt->bindParam(":foodLat", $this->foodLat);
        $stmt->bindParam(":foodLng", $this->foodLng);
        $stmt->bindParam(":foodProvince", $this->foodProvince);

        //สั่งให้คำสั่ง SQL งานผ่านทางตัวแปรที่สร้างไว้
        if ($stmt->execute()) {
            //ส่งค่ากลับเป็น true เมื่อเพิ่มข้อมูลสำเร็จ
            return true;
        }

        //ส่งค่ากลับเป็น false เมื่อเพิ่มข้อมูลไม่สำเร็จ
        return false;
    }


    //---------------------------------------------------------
    //สร้างฟังก์ชันลบรูปภาพออก กรณีแก้ไขรูป หรือลบข้อมูล
    public function delelet_picture_diaryfood($foodId)
    {
        //สร้างตัวแปรเก็บคำสั่ง SQL
        $strSql = "SELECT foodImage FROM diaryfood_tb WHERE foodId = '{$foodId}'";

        //สรัางตัวแปรเพื่อจัดการกับคำสั่ง SQL (เป็นการเตรียมคำสั่ง SQL ให้พร้อมทำงาน)
        $stmt = $this->conn->prepare($strSql);

        //สั่งให้ SQL ทำงานผ่านตัวแปรที่จัดการกับคำสั่ง SQL
        $stmt->execute();

        //นำผลที่ได้เก็บในตัวแปร
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        //ลบไฟล์
        unlink("./../../images/{$rec['foodImage']}");
    }

    //---------------------------------------------------------
    //สร้างฟังก์ชันแก้ไขข้อมูลลงในตาราง diaryfood_tb 
    //ใช้คู่กับไฟล์ api_update_diaryfood.php
    public function update_diaryfood()
    {
        //สร้างตัวแปรและกำหนดค่าเป็นคำสั่ง SQL แก้ไขข้อมูลลงในตาราง diaryfood_tb
        $strSql = "";
        if (strlen($this->foodImage) != 0) {
            $strSql = "UPDATE diaryfood_tb SET
                            foodShopname=:foodShopname,
                            foodImage=:foodImage,
                            foodPay=:foodPay,
                            foodMeal=:foodMeal,
                            foodDate=:foodDate,
                            foodLat=:foodLat,
                            foodLng=:foodLng,
                            foodProvince=:foodProvince
                        WHERE
                            foodId=:foodId";
        } else {
            $strSql = "UPDATE diaryfood_tb SET
                            foodShopname=:foodShopname,
                            foodPay=:foodPay,
                            foodMeal=:foodMeal,
                            foodDate=:foodDate,
                            foodLat=:foodLat,
                            foodLng=:foodLng,
                            foodProvince=:foodProvince
                        WHERE
                            foodId=:foodId";
        }

        //sanitize ทำความสะอาดข้อมูล เพื่อกรองข้อมูลป้องกัน SQL injection ก่อนที่จะนำข้อมูลแก้ไขลงในตาราง
        $this->foodId = intval(htmlspecialchars(strip_tags($this->foodId)));
        $this->foodShopname = htmlspecialchars(strip_tags($this->foodShopname));
        if (strlen($this->foodImage) != 0) {
            $this->foodImage = htmlspecialchars(strip_tags($this->foodImage));
            //เรียกฟังก์ชันเพื่อลบรูปออก กรณีมีการแก้ไขรูป
            $this->delelet_picture_diaryfood($this->foodId);
        }
        $this->foodPay = htmlspecialchars(strip_tags($this->foodPay));
        $this->foodMeal = intval(htmlspecialchars(strip_tags($this->foodMeal)));
        $this->foodDate = htmlspecialchars(strip_tags($this->foodDate));
        $this->foodLat = doubleval(htmlspecialchars(strip_tags($this->foodLat)));
        $this->foodLng = doubleval(htmlspecialchars(strip_tags($this->foodLng)));
        $this->foodProvince = htmlspecialchars(strip_tags($this->foodProvince));

        //สร้างตัวแปรเพื่อจัดการกับคำสั่ง SQL 
        $stmt = $this->conn->prepare($strSql);

        //bind values เพื่อกำหนดข้อมูลให้กับ parameter ที่กำหนดไว้ที่คำสั่ง SQL
        $stmt->bindParam(":foodId", $this->foodId);
        $stmt->bindParam(":foodShopname", $this->foodShopname);
        if (strlen($this->foodImage) != 0) {
            $stmt->bindParam(":foodImage", $this->foodImage);
        }
        $stmt->bindParam(":foodPay", $this->foodPay);
        $stmt->bindParam(":foodMeal", $this->foodMeal);
        $stmt->bindParam(":foodDate", $this->foodDate);
        $stmt->bindParam(":foodLat", $this->foodLat);
        $stmt->bindParam(":foodLng", $this->foodLng);
        $stmt->bindParam(":foodProvince", $this->foodProvince);

        //สั่งให้คำสั่ง SQL งานผ่านทางตัวแปรที่สร้างไว้
        if ($stmt->execute()) {
            //ส่งค่ากลับเป็น true เมื่อแก้ไขข้อมูลสำเร็จ
            return true;
        }

        //ส่งค่ากลับเป็น false เมื่อแก้ไขข้อมูลไม่สำเร็จ
        return false;
    }

    //---------------------------------------------------------
    //สร้างฟังก์ชันลบข้อมูลลงในตาราง diaryfood_tb 
    //ใช้คู่กับไฟล์ api_delete_diaryfood.php
    public function delete_diaryfood()
    {
        //สร้างตัวแปรและกำหนดค่าเป็นคำสั่ง SQL ลบข้อมูลลงในตาราง diaryfood_tb
        $strSql = "DELETE FROM diaryfood_tb WHERE foodId=:foodId";

        //sanitize ทำความสะอาดข้อมูล เพื่อกรองข้อมูลป้องกัน SQL injection ก่อนที่จะนำข้อมูลลบลงในตาราง
        $this->foodId = intval(htmlspecialchars(strip_tags($this->foodId)));
        //เรียกฟังก์ชันเพื่อลบรูปออก กรณีมีการแก้ไขรูป
        $this->delelet_picture_diaryfood($this->foodId);

        //สร้างตัวแปรเพื่อจัดการกับคำสั่ง SQL 
        $stmt = $this->conn->prepare($strSql);

        //bind values เพื่อกำหนดข้อมูลให้กับ parameter ที่กำหนดไว้ที่คำสั่ง SQL
        $stmt->bindParam(":foodId", $this->foodId);

        //สั่งให้คำสั่ง SQL งานผ่านทางตัวแปรที่สร้างไว้
        if ($stmt->execute()) {
            //ส่งค่ากลับเป็น true เมื่อลบข้อมูลสำเร็จ
            return true;
        }

        //ส่งค่ากลับเป็น false เมื่อลบข้อมูลไม่สำเร็จ
        return false;
    }
}

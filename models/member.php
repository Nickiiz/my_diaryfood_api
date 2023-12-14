<?php

class Member
{
    //สร้างตัวแปรที่ใช้เป็น connection ทำงานกับ ฐานข้อมูล
    private $conn;

    //สร้างตัวแปรที่จะทำงานกับข้อมูล(field)ของตาราง member_tb
    public $memId;
    public $memFullname;
    public $memUsername;
    public $memPassword;
    public $memEmail;
    public $memAge;

    //สร้างตัวแปรตัวแปรนี้เอาไว้ใช้สำหรับข้อมูลอื่นๆ
    public $message;

    //สร้างคอนสตรักเตอร์ เพื่อกำหนดการเชื่อมต่อฐานข้อมูลที่จะทำงานด้วย
    public function __construct($db)
    {
        $this->conn = $db;
    }


    //ฟังก์ชันต่างๆที่ใช้คู่กับ API
    //ฟังก์ชัน CheckLogin
    function checklogin()
    {
        //สร้างตัวแปรเก็บคำสั่ง SQL
        $strSql = "SELECT * FROM member_tb Where memUsername=:memUsername
        and memPassword=:memPassword";


        //sanitize ทำความสะอาดข้อมูล เพื่อกรองข้อมูลป้องกัน SQL injection ก่อนที่จะนำข้อมูลลงในตาราง
        $this->memUsername = htmlspecialchars(strip_tags($this->memUsername));
        $this->memPassword = htmlspecialchars(strip_tags($this->memPassword));


        //สรัางตัวแปรเพื่อจัดการกับคำสั่ง SQL (เป็นการเตรียมคำสั่ง SQL ให้พร้อมทำงาน)
        $stmt = $this->conn->prepare($strSql);

        //bind values เพื่อกำหนดข้อมูลให้กับ parameter ที่กำหนดไว้ที่คำสั่ง SQL
        $stmt->bindParam(":memUsername", $this->memUsername);
        $stmt->bindParam(":memPassword", $this->memPassword);

        //สั่งให้ SQL ทำงานผ่านตัวแปรที่จัดการกับคำสั่ง SQL
        $stmt->execute();

        //ส่งผลที่ได้กลับไปที่จุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }
}

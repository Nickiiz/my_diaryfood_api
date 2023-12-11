<?php
//ไฟล์นี้ใช้สำหรับการเชื่อมต่อไปยังฐานข้อมูล
class ConnectDB
{
    //ประกาศตัวแปรเก็บค่าต่างๆ ที่จะต้องใช้ในการติดต่อกับฐานข้อมูล
    private $host = "localhost";
    private $uname = "root";
    private $pword = "";
    private $dbname = "mydiaryfood_db";

    //ประกาศตัวแปรเพื่อใช้สำหรับการติดต่อกับฐานข้อมูล
    public $conn;

    //สร้างฟังก์ชันที่ใช้ติดต่อกับฐานข้อมูล
    public function getConnectionDB()
    {
        $this->conn = null;

        try {
            //ติดต่อฐานข้อมูล
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->uname, $this->pword);

            
            //เซ็ตตัวอักขระ เพื่อให้อ่านเขียนไทยได้แบบไม่เป็นภาษามนุษย์ต่างดาว ^_^
            $this->conn->exec("set names utf8");

            //log ดูผลว่า connect ได้/ไม่ได้ ก็ให้คอมเมนต์ออก
            //echo "ติดต่อ DB OK";
        } catch (PDOException $ex) {
            //log ดูผลว่า connect ได้/ไม่ได้ ก็ให้คอมเมนต์ออก
            //echo "ติดต่อ DB NOT OK {$ex}";
        }

        return $this->conn;
    }
}

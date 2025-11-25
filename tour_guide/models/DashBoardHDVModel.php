<?php
class HDVDashboard
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }


}
?>
<?php
// có class chứa các function thực thi xử lý logic 
class ProductController
{
    public $modelProduct;

    public function __construct()
    {
        $this->modelProduct = new ProductModel();
    }

    public function Home()
    {
        $title = "Đây là trang chủ hướng dẫn viên nhé hahaa";
        $thoiTiet = "Hôm nay trời có vẻ là mưa";
        require_once './views/trangchu.php';
    }
}

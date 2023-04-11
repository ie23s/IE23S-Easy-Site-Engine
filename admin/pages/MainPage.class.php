<?php

namespace ie23s\shop\admin\pages;

require_once __SHOP_DIR__ . 'admin/pages/AdminPage.php';

class MainPage extends AdminPage
{

    function getPage(): string
    {
        return "Main page";
    }
}
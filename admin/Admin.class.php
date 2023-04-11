<?php

namespace ie23s\shop\admin;

require_once __SHOP_DIR__ . 'admin/pages/Pages.class.php';
require_once __SHOP_DIR__ . 'admin/api/AdminApi.php';

use ie23s\shop\admin\api\AdminApi;
use ie23s\shop\admin\pages\Pages;
use ie23s\shop\system\Component;
use ie23s\shop\system\System;

class Admin extends Component
{
    public function __construct(System $system)
    {
        parent::__construct($system);
        new Pages('admin', $system->getPages(), 'administrator', 'adm');

    }

    /**
     * @return void
     */
    public function load()
    {
        (new AdminApi($this->getSystem()))->loadApiMethods();
    }

}
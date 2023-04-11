<?php

namespace ie23s\shop\admin\api;
require_once __SHOP_DIR__ . 'system/api/ApiAbstract.php';
require_once __SHOP_DIR__ . 'admin/api/ProductsApi.class.php';
require_once __SHOP_DIR__ . 'admin/api/ProductApi.class.php';

use ie23s\shop\system\System;

class AdminApi
{
    private System $system;

    /**
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    public function loadApiMethods(): void
    {
        if (!$this->system->getAuth()->getCurrentUser()->hasPermission('admin'))
            return;
        //$this->system->getApi()->addPath('products', new ProductsApi($this->system));
        //$this->system->getApi()->addPath('product', new ProductApi($this->system));
    }
}
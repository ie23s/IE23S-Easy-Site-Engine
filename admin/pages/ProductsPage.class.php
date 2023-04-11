<?php

namespace ie23s\shop\admin\pages;

use ie23s\shop\engine\utils\breadcrumbs\categories\CategoriesEngine;
use ie23s\shop\engine\utils\breadcrumbs\product\Product;
use ie23s\shop\engine\utils\breadcrumbs\product\ProductEngine;

require_once __SHOP_DIR__ . 'admin/pages/ProductsPage.class.php';

class ProductsPage extends AdminPage
{

    private CategoriesEngine $categoriesEngine;
    private ProductEngine $productsEngine;

    function getPage(): string
    {
        $this->categoriesEngine = $this->getEngine()->getCategoriesEngine();
        $this->productsEngine = $this->getEngine()->getProductEngine();

        $theme = $this->getSystem()->getPages()->getTheme();
        if (@$_POST['type'] == 'edit')
            $this->edit();
        elseif (@$_POST['type'] == 'add')
            $this->add();
        elseif (@$_POST['type'] == 'remove')
            $this->remove();
        $theme->addObject('admin_products_edit', $this->productsEngine->getAllProducts());
        $theme->addObject('admin_cats_list', $this->categoriesEngine->getCategories());
        $theme->getSmarty()->assign('lang', $this->getSystem()->getLang());

        return $theme->getTpl('admin/products');
    }

    private function edit()
    {
        $product = $this->productsEngine->getProductById($_POST['id']);

        $product->setCost($_POST['cost']);
        $product->setArt($_POST['art']);
        $product->setCode($_POST['code']);
        $product->setSold($_POST['sold']);
        $product->setBalance($_POST['balance']);
        $product->setCategory($_POST['category']);
        $names = [['lang_id' => 1, 'value' => $_POST['display_name']]];
        $descs = [['lang_id' => 1, 'value' => $_POST['description']]];
        $this->productsEngine->updateProduct($product, $names, $descs);
    }

    private function add()
    {
        $product = new Product(0, $_POST['cost'], $_POST['art'], $_POST['code'], $_POST['sold'], $_POST['balance'],
            $_POST['category']);
        $names = [['lang_id' => 1, 'value' => $_POST['display_name']]];
        $descs = [['lang_id' => 1, 'value' => $_POST['description']]];
        $this->productsEngine->createProduct($product, $names, $descs);
    }

    private function remove()
    {
        $product = $this->productsEngine->getProductById($_POST['id']);
        $this->productsEngine->removeProduct($product);
    }
}
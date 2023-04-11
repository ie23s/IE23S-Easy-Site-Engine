<?php

namespace ie23s\shop\admin\api;

use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;
use ie23s\shop\engine\utils\breadcrumbs\product\Product;
use ie23s\shop\engine\utils\breadcrumbs\product\ProductEngine;
use ie23s\shop\system\System;
use Simplon\Mysql\MysqlException;

class ProductApi extends ApiAbstract
{
    private ProductEngine $productEngine;

    public function __construct(System $system)
    {
        parent::__construct($system);
        $this->productEngine = $system->getEngine()->getProductEngine();
    }

    public function get(): string
    {
        try {
            $product = $this->productEngine->getProductByIdAsArray($this->getRequest('id'));
        } catch (MysqlException $e) {
            return $this->withCode(500);
        }
        if ($product == null) {
            return $this->withCode(404);
        }
        return json_encode($product);
    }

    public function post(): string
    {
        $product = new Product(0, $this->getRequest('cost'), $this->getRequest('art'),
            $this->getRequest('code'), 0,
            $this->getRequest('balance'), $this->getRequest('category'),
            json_decode($this->getRequest('photos')));
        $names = [['lang_id' => 1, 'value' => $this->getRequest('display_name')]];
        $descs = [['lang_id' => 1, 'value' => $this->getRequest('description')]];
        try {
            $this->productEngine->createProduct($product, $names, $descs);
        } catch (MysqlException $e) {
            return $this->withCode(500);
        }
        return $this->withCode(200, json_encode(['id' => $product->getId()]));
    }

    public function put(): string
    {
        try {
            $product = $this->productEngine->getProductById($this->getRequest('id'));

            parse_str(file_get_contents("php://input"), $putData);

            if ($product == null) {
                return $this->withCode(404);
            }
            $product->setCost($this->getRequest('cost'));
            $product->setArt($this->getRequest('art'));
            $product->setCode($this->getRequest('code'));
            $product->setBalance($this->getRequest('balance'));
            $product->setCategory($this->getRequest('category'));
            $product->setPhotos(json_decode($this->getRequest('photos')));
            $names = [['lang_id' => 1, 'value' => $this->getRequest('display_name')]];
            $descs = [['lang_id' => 1, 'value' => $this->getRequest('description')]];
            $this->productEngine->updateProduct($product, $names, $descs);
            return $this->withCode(200);
        } catch (MysqlException $e) {
            return $this->withCode(500);
        }
    }

    public function delete(): string
    {
        try {
            $product = $this->productEngine->getProductById($this->getRequest('id'));
            if ($product == null) {
                return $this->withCode(404);
            }
            $this->productEngine->removeProduct($product);
            return $this->withCode(200);
        } catch (MysqlException $e) {
            return $this->withCode(500);
        }
    }
}
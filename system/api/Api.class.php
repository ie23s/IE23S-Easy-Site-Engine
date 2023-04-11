<?php

namespace ie23s\shop\system\api;
require_once __SHOP_DIR__ . "system/api/ApiInterface.php";

require_once __SHOP_DIR__ . "system/api/ApiPage.class.php";

use ie23s\shop\system\Component;
use ie23s\shop\system\pages\Pages;

class Api extends Component
{
    private array $apiPathList = [];

    /**
     * @inheritDoc
     */
    public function load()
    {
        $apiPage = new ApiPage('api', $this->getSystem()->getPages(), 'api');
        $apiPage->setApi($this);
    }

    public function addPath($path, ApiInterface $api)
    {
        $this->apiPathList[$path] = $api;
    }

    public function getPath(array $request): ?ApiInterface
    {
        array_shift($request);
        return @$this->apiPathList[Pages::toPath($request)];
    }
}
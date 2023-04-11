<?php

namespace ie23s\shop\system\files;

require_once __SHOP_DIR__ . '/system/files/UploadApi.php';

use ie23s\shop\system\Component;

class Files extends Component
{

    /**
     * @return void
     */
    public function load()
    {
        $this->system->getApi()->addPath('uploadfiles', new UploadApi($this->system));
    }
}
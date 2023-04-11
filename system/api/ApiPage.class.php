<?php

namespace ie23s\shop\system\api;

use ie23s\shop\system\pages\Page;

class ApiPage extends Page
{
    private Api $api;

    /**
     * @param array $request
     * @return string
     */
    public function request(array $request): string
    {
        define('offTimer', 'off');
        $this->needTheme(false);
        $this->addHeader('Content-Type: application/json; charset=utf-8');
        $module = $this->api->getPath($request);
        if ($module != null)
            switch (getenv('REQUEST_METHOD')) {
                case 'GET':
                    return $module->get();
                case 'POST':
                    return $module->post();
                case 'PUT':
                    return $module->put();
                case 'DELETE':
                    return $module->delete();
            }

        return json_encode(['error' => 404, 'text' => 'Not found']);
    }

    /**
     * @param Api $api
     */
    public function setApi(Api $api): void
    {
        $this->api = $api;
    }

}
<?php

namespace ie23s\shop\engine\utils\breadcrumbs\api;

use ie23s\shop\engine\utils\breadcrumbs\Engine;
use ie23s\shop\system\api\ApiInterface;
use ie23s\shop\system\Codes;
use ie23s\shop\system\System;

abstract class ApiAbstract implements ApiInterface
{
    private System $system;
    private Engine $engine;
    private array $request = [];
    private int $code = 200;

    /**
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
        $this->engine = $system->getEngine();
        parse_str(file_get_contents("php://input"), $this->request);
    }

    /**
     * @return Engine
     */
    public function getEngine(): Engine
    {
        return $this->engine;
    }

    /**
     * @return System
     */
    public function getSystem(): System
    {
        return $this->system;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function hasRequest(string $param): bool
    {
        if ($this->getRequest($param) == null)
            return false;
        return true;
    }

    public function getRequest(string $param)
    {
        @        $res = $this->request[$param];
        if ($res == null)
            @            $res = $_REQUEST[$param];
        return $res;
    }

    public function withCode($code, $text = null)
    {
        $this->setCode($code);
        http_response_code($code);
        if ($text != null)
            return json_encode(['code' => $code, 'text' => $text]);
        return json_encode(['code' => $code, 'text' => Codes::getCodeText($code)]);
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function withData($code, array $data)
    {
        $this->setCode($code);
        http_response_code($code);
        return json_encode(['code' => $code, 'text' => Codes::getCodeText($code), 'data' => $data]);
    }
}
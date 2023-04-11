<?php

namespace ie23s\shop\system\api;

interface ApiInterface
{
    public function get(): string;

    public function post(): string;

    public function put(): string;

    public function delete(): string;

    public function code(): int;
}
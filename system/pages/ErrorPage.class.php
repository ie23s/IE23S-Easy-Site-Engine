<?php

namespace ie23s\shop\system\pages;

use Simplon\Mysql\MysqlException;
use SmartyException;

class ErrorPage extends Page
{
    private int $error;
    private string $text;

    /**
     * @throws SmartyException
     */
    public function request(array $request): string
    {
        $theme = new Theme();
        $theme->addBlock('error_num', $this->error);
        $theme->addBlock('error_text', $this->text);
        $this->getPages()->setTitle($this->error);
        $this->needTheme(false);
        return $theme->getTpl('error');
    }

    public function setError(int $error, string $text = ''): void
    {
        $this->error = $error;
        $this->text = $text;
        http_response_code($error);
        try {
            $this->getPages()->unload();
        } catch (SmartyException|MysqlException $e) {
            echo($e->getTraceAsString());
        }
//        $this->getSystem()->getComponent('mail')->sendMail(
//            ['name' => 'Ilya Evtukhov', 'email' => 'evtukhov23@gmail.com'],
//            'An error ' . $error, "Haha! You've got an error:<br>{$text}");

        die();
    }

}
<?php

namespace ie23s\shop\admin\pages;

use ie23s\shop\system\lang\Lang;

class LangPage extends AdminPage
{
    private Lang $lang;

    function getPage(): string
    {
        $this->lang = $this->getSystem()->getLang();
        $theme = $this->getSystem()->getPages()->getTheme();
        if (@$_POST['type'] == 'edit')
            $this->edit();
        elseif (@$_POST['type'] == 'add')
            $this->add();
        elseif (@$_POST['type'] == 'remove')
            $this->remove();
        $theme->addObject('admin_lang_edit', $this->lang->getAllLang());

        return $theme->getTpl('admin/language');

    }

    private function edit()
    {
        $this->lang->updateRow($_POST['key'], $_POST['value']);
    }

    private function add()
    {
        $this->lang->addRow($_POST['key'], $_POST['value']);
    }

    private function remove()
    {
        echo 1;
        $this->lang->remRow($_POST['key']);
    }

}
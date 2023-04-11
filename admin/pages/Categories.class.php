<?php

namespace ie23s\shop\admin\pages;

use Category;
use ie23s\shop\engine\utils\breadcrumbs\categories\CategoriesEngine;
use Simplon\Mysql\MysqlException;
use SmartyException;

class Categories extends AdminPage
{
    private CategoriesEngine $categoriesEngine;

    /**
     * @return string
     * @throws MysqlException|SmartyException
     */
    function getPage(): string
    {
        $this->categoriesEngine = $this->getEngine()->getCategoriesEngine();

        $theme = $this->getSystem()->getPages()->getTheme();
        if (@$_POST['type'] == 'add')
            $this->add();
        elseif (@$_POST['type'] == 'remove')
            $this->remove();
        elseif (@$_POST['type'] == 'edit')
            $this->edit();
        $theme->addObject('admin_cats_edit', $this->editableCategories());
        $theme->addObject('admin_cats_list', $this->categoriesEngine->getCategories());

        return $theme->getTpl('admin/categories');
    }

    /**
     * @throws MysqlException
     */
    private function add()
    {
        $category = new Category(0, $_POST['name'], $_POST['parent'], [], $_POST['display_name']);
        //$names = [['lang_id' => 1, 'value' => $_POST['display_name']]];
        $this->categoriesEngine->createCategory($category);
    }

    /**
     * @throws MysqlException
     * @throws SmartyException
     */
    private function remove()
    {
        $this->categoriesEngine->removeCategory($_POST['id']);
    }

    /**
     * @throws MysqlException
     */
    private function edit()
    {
        $category = $this->categoriesEngine->getCategory($_POST['id']);

        $category->setName($_POST['name']);
        $category->setDisplayName($_POST['display_name']);
        $category->setParentId($_POST['parent']);
//        $names = [['lang_id' => 1, 'value' => $_POST['display_name']]];
        $this->categoriesEngine->updateCategory($category);
    }

    private function editableCategories(): array
    {
        $r = array();
        /** @var Category $category */
        foreach ($this->categoriesEngine->getCategories() as $category) {
            $r[] = array(
                'id' => $category->getId(),
                'name' => $category->getName(),
                'display_name' => $category->getDisplayName(),
                'parent_id' => $category->getParentId()

            );
        }
        return $r;
    }
}
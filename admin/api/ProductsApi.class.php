<?php

namespace ie23s\shop\admin\api;


use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;

class ProductsApi extends ApiAbstract
{
    /**
     * @return string
     * @throws MysqlException
     */
    public function get(): string
    {
        /**
         * @var MySQL $db
         */
        $db = $this->getSystem()->getComponent('database')->getConn();
        $query = '%' . str_replace(' ', '%', trim($this->getRequest('q'))) . '%';
        $response = $db->fetchRowMany('SELECT *, (SELECT language_editable.value
                        FROM language_editable
                        WHERE language_editable.`type` = \'product-name\' AND `external_id` = products.id
                        AND lang_id = :lang_id)
                            as display_name,
                        (SELECT language_editable.value
                        FROM language_editable
                        WHERE language_editable.`type` = \'product-description\' AND `external_id` = products.id
                        AND lang_id = :lang_id)
                            as description,
                        (SELECT (SELECT language_editable.value
                                FROM language_editable
                                WHERE language_editable.`type` = \'category-name\' AND `external_id` = categories.id
                                AND lang_id = :lang_id)
                         FROM categories WHERE id = products.category) as category_name
            FROM products
            WHERE id IN (SELECT `external_id` FROM `language_editable`
                        WHERE value LIKE :q) OR 
              code LIKE :q OR art LIKE :q ORDER BY `id` DESC', ['q' => $query, 'lang_id' => $this->getSystem()->getLang()->getLangId()]);
        return json_encode($response);
    }

    /**
     * @return string
     */
    public function post(): string
    {

        return $this->withCode(405);
    }

    /**
     * @return string
     */
    public function put(): string
    {
        return $this->withCode(405);
    }

    /**
     * @return string
     */
    public function delete(): string
    {
        return $this->withCode(405);
    }
}
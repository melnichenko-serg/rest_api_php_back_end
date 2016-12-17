<?php

namespace api\modules\pages;

use api\app\Model;

class PageModel
{
    public function getPageByParam($field, $param)
    {
        $query = "select id, title, baner_title, baner_name, content from pages where {$field} = :param";
        $pageContent = $this->getOne($query, [":param" => $param]);
        return $pageContent ?? false;
    }
}

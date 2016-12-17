<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 06.12.16
 * Time: 11:12
 */

namespace api\app;


trait View
{
    public function view(string $viewName)
    {
        $data = [
            'title' => $viewName,
            'js' => $viewName . ".js",
            'css' => $viewName . ".css"
        ];
        include_once "public/pages/includes/header.php";
        $viewFile = "public/pages/pages_temp/{$viewName}.php";
        if (file_exists($viewFile)) include_once $viewFile;
        include_once "public/pages/includes/footer.php";
    }
}
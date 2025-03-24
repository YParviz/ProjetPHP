<?php namespace Util;

class View {

    public function render($path, $data = false)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
        }

        $filepath = __DIR__."/../app/Views/$path.php";
        require __DIR__."/../app/Views/navbar.php";
        renderNavbar();

        if (file_exists($filepath)) {
            require $filepath;
        } else {
            die("View: $filepath not found!");
        }

    }
}

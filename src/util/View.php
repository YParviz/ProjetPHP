<?php namespace Util;

class View
{

    public function render($path, $data = false): void
    {
        if ($data) {
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
        }

        $filepath = "../app/views/$path.php";

        if (file_exists($filepath)) {
            require $filepath;
        } else {
            die("View: $path not found!");
        }

    }
}

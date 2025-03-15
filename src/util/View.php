<?php namespace Util;

class View {

    public function render($path, $data = false)
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

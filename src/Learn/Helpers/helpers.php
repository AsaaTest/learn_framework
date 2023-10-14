<?php

if (!function_exists('dd')) {
    /**
     * Function allow debug
     *
     * @return void
     */
    function dd()
    {
        echo '<div style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ccc;">';

        foreach (func_get_args() as $data) {
            echo '<pre>';
            echo '<strong>Debugging Information:</strong><br>';
            echo '<strong>Type:</strong> ' . gettype($data) . '<br>';
            echo '<strong>Value:</strong> ';
            var_dump($data);
            echo '</pre>';
        }

        echo '</div>';

        die();
    }
}

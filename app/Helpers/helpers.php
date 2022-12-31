 <?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Function to set sidebar parent active based on given url
 */
if (!function_exists('active_sidebar_parent')) {
    function active_sidebar_parent($url) {
        $name = Route::currentRouteName();
        for ($a = 0; $a < count($url); $a++) {
            if ($name == $url[$a]) {
                return 'active';
            }
        }

        return '';
    }
}

/**
 * Function to set breadcrumb
 */
if (!function_exists('breadcrumb')) {
    function breadcrumb($lists) {
        $li = "";
        foreach ($lists as $list) {
            $text = '';
            if ($list['active']) {
                $text = '<a href="'. $list['href'] .'">'. $list['name'] .'</a>';
            } else {
                $text = $list['name'];
            }
            $li .= "<li>". $text ."</li>";
        }
        config(['app.breadcrumb' => $li]);
    }
}

/**
 * Function to set log
 */
if (!function_exists('setup_log')) {
    function setup_log($msg, $data)
    {
        Log::debug($msg . ' ' . date('Y-m-d H:i:s'), ['data' => $data]);
    }
}
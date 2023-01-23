 <?php

use App\Http\Services\WalletService;
use App\Models\Employees;
use App\Models\Income;
use App\Models\Intitution;
use App\Models\Payments;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/**
 * Function to set sidebar parent active based on given url
 */
if (! function_exists('active_sidebar_parent')) {
    function active_sidebar_parent($url)
    {
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
 * Function to set sidebar child active based on given url
 */
if (! function_exists('active_sidebar_child')) {
    function active_sidebar_child($url)
    {
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
if (! function_exists('breadcrumb')) {
    function breadcrumb($lists)
    {
        $li = '';
        foreach ($lists as $list) {
            $text = '';
            if ($list['active']) {
                $text = '<a href="'.$list['href'].'">'.$list['name'].'</a>';
            } else {
                $text = $list['name'];
            }
            $li .= '<li>'.$text.'</li>';
        }
        config(['app.breadcrumb' => $li]);
    }
}

/**
 * Function to set log
 */
if (! function_exists('setup_log')) {
    function setup_log($msg, $data)
    {
        Log::debug($msg.' '.date('Y-m-d H:i:s'), ['data' => $data]);
    }
}

if (! function_exists('render_lang')) {
    function render_lang($name)
    {
        return __($name);
    }
}

/**
 * Function to generate invoice number
 *
 * @return string
 */
if (! function_exists('generate_invoice_number')) {
    function generate_invoice_number($additional = [])
    {
        $count = Payments::select('id')
            ->count();
        $count++;

        $prefix = 'INV/';
        $number = $prefix.sprintf('%06s', $count);

        return $number;
    }
}

if (!function_exists('generate_child_invoice')) {
    function generate_child_invoice($number, $max)
    {
        $res = [];
        $numbers = [];
        $words = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $word = '';
        for ($a = 0; $a < $max; $a++) {
            $res[] = $words[$a];
            $numbers[$a] = $number;
        }
        for ($b = 0; $b < count($res); $b++) {
            $numbers[$b] = $numbers[$b] . '-' . $res[$b];
        }

        return $numbers;
    }
}

if (! function_exists('generate_indo_date')) {
    function generate_indo_date($date)
    {
        $y = date('Y', strtotime($date));
        $m = generate_indo_month(date('M', strtotime($date)));
        $d = date('d', strtotime($date));

        return $d.' '.$m.' '.$y;
    }
}

if (! function_exists('generate_indo_month')) {
    function generate_indo_month($month)
    {
        switch ($month) {
            case 'Jan':
                $m = 'Januari';
                break;

            case 'Feb':
                $m = 'Febuari';
                break;

            case 'Mar':
                $m = 'Maret';
                break;

            case 'Apr':
                $m = 'April';
                break;

            case 'May':
                $m = 'Mei';
                break;

            case 'Jun':
                $m = 'Juni';
                break;

            case 'Jul':
                $m = 'Juli';
                break;

            case 'Aug':
                $m = 'Agustus';
                break;

            case 'Sep':
                $m = 'September';
                break;

            case 'Oct':
                $m = 'Oktober';
                break;

            case 'Nov':
                $m = 'November';
                break;

            case 'Dec':
            $m = 'Desember';
            break;

            default:
                $m = 'Not set';
                break;
        }

        return $m;
    }
}

if (!function_exists('generate_sidebar_income')) {
    function generate_sidebar_income()
    {
        $user = auth()->user();
        $institutions = Intitution::active()->get();
        Redis::set('institutions', json_encode($institutions));
    }
}

if (!function_exists('add_zero')) {
    function add_zero($val)
    {
        $res = $val;
        if ($val < 10) {
            $res = '0' . (string) $val;
        }

        return $res;
    }
}

if (!function_exists('generate_active_sidebars')) {
    function generate_active_sidebars($institutions)
    {
        $data = collect($institutions)->map(function($item) {
            return 'incomes.index.' . $item['id'];
        })->all();

        return $data;
    }
}

if (!function_exists('get_user_saldo')) {
    function get_user_saldo()
    {
        $user = auth()->user();
        $saldo = 'Rp. 0';

        if ($user) {
            $id = $user->id;
    
            /**
             * TODO: For now set just from 'Employees' Table, next fetch from Internal_users, external_users and Employees
             */
            $employee = Employees::select('id')
                ->where('user_id', $id)
                ->first();
    
            if ($employee) {
                $user_id = $employee->id;
                $type = $user->user_type == 1 ? 'internal' : 'external';
                $wallet = new WalletService();
        
                $saldo = $wallet->wallet_saldo($user_id, $type);
            }
        }

        return $saldo;
    }
}
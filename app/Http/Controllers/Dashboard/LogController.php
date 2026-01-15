<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        return view('log');
    }

    // System Logs
    public function system()
    {
        $output = shell_exec('sudo tail -n 50 /var/log/syslog');
        return response()->json(['output' => $output]);
    }

    public function auth()
    {
        $output = shell_exec('sudo tail -n 50 /var/log/auth.log');
        return response()->json(['output' => $output]);
    }

    public function kern()
    {
        $output = shell_exec('sudo tail -n 50 /var/log/kern.log');
        return response()->json(['output' => $output]);
    }

    // Apache Logs
    public function apacheAccess()
    {
        $output = shell_exec('sudo tail -n 50 /var/log/apache2/access.log');
        return response()->json(['output' => $output]);
    }

    public function apacheError()
    {
        $output = shell_exec('sudo tail -n 50 /var/log/apache2/error.log');
        return response()->json(['output' => $output]);
    }

   

    // Laravel Logs
    public function laravel()
    {
        $path = storage_path('logs/laravel.log');
        $output = file_exists($path) ? shell_exec('tail -n 50 ' . $path) : "No Laravel log found.";
        return response()->json(['output' => $output]);
    }
}

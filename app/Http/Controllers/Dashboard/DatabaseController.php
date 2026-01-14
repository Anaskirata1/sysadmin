<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class DatabaseController extends Controller
{
    public function index()
    {
        return view('database');
    }

    public function install()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/db_install.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function enable()
    {
        $output = shell_exec("sudo systemctl start mysql 2>&1");
        return response()->json(['output' => $output]);
    }

    public function disable()
    {
        $output = shell_exec("sudo systemctl stop mysql 2>&1");
        return response()->json(['output' => $output]);
    }

    public function status()
    {
        $output = shell_exec("systemctl status mysql 2>&1");
        return response()->json(['output' => $output]);
    }

    public function list()
    {
        $databases = DB::select("SHOW DATABASES");
        $dbs = [];
        foreach ($databases as $db) {
            $dbs[] = ['name' => $db->Database];
        }
        return response()->json(['databases' => $dbs]);
    }

    public function create(Request $request)
    {
        $name = $request->input('name');
        DB::statement("CREATE DATABASE `$name`");
        return response()->json(['output' => "Database $name created"]);
    }

    public function delete(Request $request)
    {
        $name = $request->input('name');
        DB::statement("DROP DATABASE `$name`");
        return response()->json(['output' => "Database $name deleted"]);
    }

    public function grant(Request $request)
    {
        $user = $request->input('user', 'aya1');
        DB::statement("GRANT ALL PRIVILEGES ON *.* TO '$user'@'localhost'");
        DB::statement("FLUSH PRIVILEGES");
        return response()->json(['output' => "Privileges granted to $user@localhost"]);
    }
}
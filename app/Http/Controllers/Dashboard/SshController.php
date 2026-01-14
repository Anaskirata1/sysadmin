<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SshController extends Controller
{
    public function index()
    {
        return view('ssh');
    }

    public function install()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_install.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function enable()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_enable.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function disable()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_disable.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function status()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_status.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function listKeys()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_list_keys.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function createKey(Request $request)
    {
        $name = $request->input('name');
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_create_key.sh " . escapeshellarg($name) . " 2>&1");
        return response()->json(['output' => $output]);
    }

    public function deleteKey(Request $request)
    {
        $name = $request->input('name');
        $output = shell_exec("sudo /home/aya/sysadmin/ssh_delete_key.sh " . escapeshellarg($name) . " 2>&1");
        return response()->json(['output' => $output]);
    }
}
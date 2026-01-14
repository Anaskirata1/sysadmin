<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirewallController extends Controller
{
  
    public function index()
    {
        return view('firewall');
    }

    public function install()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/fw_install.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function enable()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/fw_enable.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function disable()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/fw_disable.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function status()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/fw_status.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function reset()
    {
        $output = shell_exec("sudo /home/aya/sysadmin/fw_reset.sh 2>&1");
        return response()->json(['output' => $output]);
    }

    public function ports()
    {
        $raw = shell_exec("sudo /home/aya/sysadmin/fw_list_ports.sh 2>&1");
        $ports = [];
        foreach (explode("\n", trim($raw)) as $line) {
            if (!$line) continue;
            [$port, $proto, $status] = preg_split('/\s+/', $line);
            $ports[] = ['port' => $port, 'protocol' => $proto, 'status' => strtoupper($status)];
        }
        return response()->json(['ports' => $ports]);
    }

    public function allow(Request $request)
    {
        $port = $request->input('port');
        $proto = $request->input('protocol', 'tcp');

        if (!preg_match('/^[0-9]{1,5}$/', $port) || !preg_match('/^(tcp|udp)$/i', $proto)) {
            return response()->json(['output' => '[ERROR] Invalid input'], 422);
        }
        $proto = strtolower($proto);

        $output = shell_exec("sudo /home/aya/sysadmin/fw_allow.sh " . escapeshellarg($port) . " " . escapeshellarg($proto) . " 2>&1");
        return response()->json(['output' => $output]);
    }

    public function deny(Request $request)
    {
        $port = $request->input('port');
        $proto = $request->input('protocol', 'tcp');

        if (!preg_match('/^[0-9]{1,5}$/', $port) || !preg_match('/^(tcp|udp)$/i', $proto)) {
            return response()->json(['output' => '[ERROR] Invalid input'], 422);
        }
        $proto = strtolower($proto);

        $output = shell_exec("sudo /home/aya/sysadmin/fw_deny.sh " . escapeshellarg($port) . " " . escapeshellarg($proto) . " 2>&1");
        return response()->json(['output' => $output]);
    }

}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function listPorts()
{
    $portsFile = '/etc/apache2/ports.conf'; // تأكد من المسار الصحيح عندك
    $ports = [];

    if (file_exists($portsFile)) {
        $lines = file($portsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/Listen\s+(\d+)/', $line, $matches)) {
                $ports[] = $matches[1];
            }
        }
    }

    return view('ports', compact('ports'));
}





public function addPort(Request $request)
{
    $port = $request->input('port');
    $output = shell_exec("sudo /home/aya/sysadmin/update_ports.sh add $port");
    return back()->with('status', $output);
}

public function deletePort($port)
{
    $output = shell_exec("sudo /home/aya/sysadmin/update_ports.sh delete $port");
    return back()->with('status', $output);
}
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{



   public function liveTop()
{
    $output = shell_exec('top -b -n 1 | head -n 30');
    return response()->json(['output' => $output]);
}
public function killProcess(Request $request)
{
    $uid = $request->input('uid');

    if (!is_numeric($uid)) {
        return response()->json(['message' => 'Invalid UID/PID'], 400);
    }

    // استدعاء سكربت خارجي بصلاحيات root
    $output = shell_exec("sudo /home/aya/sysadmin/kill_process.sh $uid");

    return response()->json(['message' => $output]);
}
public function lsblk()
    {
        try {
            $output = shell_exec('lsblk -o NAME,SIZE,TYPE,MOUNTPOINT');
            return response()->json(['output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['output' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function dfLive()
{
    $output = shell_exec("df -h");
    return response()->json(['output' => $output]);
}


//     public function dfLive() {
//     return response()->json(['output' => shell_exec("df -h")]);
// }

public function freeLive() {
    return response()->json(['output' => shell_exec("free -h")]);
}

public function uptimeLive() {
    return response()->json(['output' => shell_exec("uptime")]);
}

public function memLive() {
    return response()->json(['output' => shell_exec("ps aux --sort=-%mem | head")]);
}

public function cpuLive() {
    return response()->json(['output' => shell_exec("ps aux --sort=-%cpu | head")]);
}

public function netstatLive() {
    return response()->json(['output' => shell_exec("ss -tulnp")]);
}

public function dmesgLive()
{
    // عرض آخر 20 سطر من رسائل الكيرنل
    $output = shell_exec("sudo /home/aya/sysadmin/dmesg_wrapper.sh");
    return response()->json(['output' => $output]);
}

public function journalLive()
{
    // عرض آخر 20 سطر من سجلات النظام
    $output = shell_exec("sudo /home/aya/sysadmin/journal_wrapper.sh");
    return response()->json(['output' => $output]);
}

public function whoLive() {
    return response()->json(['output' => shell_exec("who")]);
}

public function ipLive() {
    return response()->json(['output' => shell_exec("hostname -I")]);
}
    // public function showTop()
    // {
    //     // تشغيل الأمر top مرة واحدة (batch mode)
    //     $output = shell_exec('top -b -n 1');
    //     return view('dashboard.top', compact('output'));
    // }


}

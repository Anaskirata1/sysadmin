<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function index()
    {
        return view('usergroup');
    }

    public function list()
    {
        // Parse users
        $usersRaw = explode("\n", trim(shell_exec("getent passwd")));
        $users = [];
        foreach ($usersRaw as $line) {
            if (!$line) continue;
            [$username, $password, $uid, $gid, $gecos, $home, $shell] = explode(":", $line);
            $users[] = [
                'username' => $username,
                'uid' => $uid,
                'gid' => $gid,
                'home' => $home,
                'shell' => $shell,
                'info' => $gecos,
            ];
        }

        // Parse groups
        $groupsRaw = explode("\n", trim(shell_exec("getent group")));
        $groups = [];
        foreach ($groupsRaw as $line) {
            if (!$line) continue;
            [$groupname, $password, $gid, $members] = explode(":", $line);
            $groups[] = [
                'groupname' => $groupname,
                'gid' => $gid,
                'members' => $members,
            ];
        }

        return response()->json([
            'users' => $users,
            'groups' => $groups
        ]);
    }

    public function createUser(Request $request)
    {
        $username = $request->input('username');
        $uid = $request->input('uid');
        $gid = $request->input('gid');
        $home = $request->input('home');
        $shell = $request->input('shell');
        $group = $request->input('group');
        $createHome = $request->input('createHome') ? "--create-home" : "";

        $cmd = "sudo useradd $createHome";
        if ($uid) $cmd .= " -u $uid";
        if ($gid) $cmd .= " -g $gid";
        if ($home) $cmd .= " -d $home";
        if ($shell) $cmd .= " -s $shell";
        if ($group) $cmd .= " -G $group";
        $cmd .= " $username";

        $output = shell_exec($cmd . " 2>&1");
        return response()->json(['output' => $output ?: "User $username created"]);
    }

    public function createGroup(Request $request)
    {
        $groupname = $request->input('groupname');
        $gid = $request->input('gid');
        $cmd = "sudo groupadd";
        if ($gid) $cmd .= " -g $gid";
        $cmd .= " $groupname";

        $output = shell_exec($cmd . " 2>&1");
        return response()->json(['output' => $output ?: "Group $groupname created"]);
    }

    public function deleteUser(Request $request)
{
    $username = $request->input('username');
    $password = $request->input('password');

    // تمرير كلمة السر إلى sudo عبر stdin
    $cmd = "echo " . escapeshellarg($password) . " | sudo -S /usr/sbin/userdel " . escapeshellarg($username);
    $output = shell_exec($cmd . " 2>&1");

    return response()->json(['output' => $output ?: "User $username deleted"]);
}

public function deleteGroup(Request $request)
{
    $groupname = $request->input('groupname');
    $password = $request->input('password');

    $cmd = "echo " . escapeshellarg($password) . " | sudo -S /usr/sbin/groupdel " . escapeshellarg($groupname);
    $output = shell_exec($cmd . " 2>&1");

    return response()->json(['output' => $output ?: "Group $groupname deleted"]);
}
}


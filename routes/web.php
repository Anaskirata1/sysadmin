<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\PortController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\FirewallController;
use App\Http\Controllers\Dashboard\DatabaseController;
use App\Http\Controllers\Dashboard\SshController;
use App\Http\Controllers\Dashboard\UserGroupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/dashboard/top/live', [App\Http\Controllers\Dashboard\DashboardController::class, 'liveTop'])->name('dashboard.top.live');
// Route::get('/dashboard/top/live', [App\Http\Controllers\Dashboard\DashboardController::class, 'liveTop'])->name('dashboard.top.live');
Route::get('/dashboard/top/live', [App\Http\Controllers\Dashboard\DashboardController::class, 'liveTop'])->name('dashboard.top.live');
Route::get('/dashboard/lsblk/live', [DashboardController::class, 'lsblk'])
    ->name('dashboard.lsblk.live');
Route::post('/dashboard/kill', [DashboardController::class, 'killProcess'])->name('dashboard.kill');
Route::get('/dashboard/df', [DashboardController::class, 'dfLive'])->name('dashboard.df.live');



Route::get('/dashboard/free', [DashboardController::class, 'freeLive'])->name('dashboard.free.live');
Route::get('/dashboard/uptime', [DashboardController::class, 'uptimeLive'])->name('dashboard.uptime.live');
Route::get('/dashboard/mem', [DashboardController::class, 'memLive'])->name('dashboard.mem.live');
Route::get('/dashboard/cpu', [DashboardController::class, 'cpuLive'])->name('dashboard.cpu.live');
Route::get('/dashboard/netstat', [DashboardController::class, 'netstatLive'])->name('dashboard.netstat.live');
Route::get('/dashboard/dmesg', [DashboardController::class, 'dmesgLive'])->name('dashboard.dmesg.live');
Route::get('/dashboard/journal', [DashboardController::class, 'journalLive'])->name('dashboard.journal.live');
Route::get('/dashboard/who', [DashboardController::class, 'whoLive'])->name('dashboard.who.live');
Route::get('/dashboard/ip', [DashboardController::class, 'ipLive'])->name('dashboard.ip.live');

Route::post('/dashboard/kill', [DashboardController::class, 'killProcess'])->name('dashboard.kill');


// Ports
Route::get('/ports', function () {
    return view('ports');
})->middleware(['auth', 'verified'])->name('ports');
Route::get('/dashboard/ports', [PortController::class, 'listPorts'])->name('ports.list');
Route::post('/dashboard/ports/add', [PortController::class, 'addPort'])->name('ports.add');
Route::delete('/dashboard/ports/{port}', [PortController::class, 'deletePort'])->name('ports.delete');

// firewall
Route::get('/firewall', [FirewallController::class, 'index'])->name('firewall.index');

Route::post('/firewall/install', [FirewallController::class, 'install'])->name('firewall.install');
Route::post('/firewall/enable', [FirewallController::class, 'enable'])->name('firewall.enable');
Route::post('/firewall/disable', [FirewallController::class, 'disable'])->name('firewall.disable');
Route::get('/firewall/status', [FirewallController::class, 'status'])->name('firewall.status');
Route::post('/firewall/reset', [FirewallController::class, 'reset'])->name('firewall.reset');

Route::get('/firewall/ports', [FirewallController::class, 'ports'])->name('firewall.ports');
Route::post('/firewall/allow', [FirewallController::class, 'allow'])->name('firewall.allow');
Route::post('/firewall/deny', [FirewallController::class, 'deny'])->name('firewall.deny');

// database
Route::prefix('database')->group(function () {
    Route::get('/', [DatabaseController::class, 'index'])->name('database.index');

    Route::post('/install', [DatabaseController::class, 'install'])->name('database.install');
    Route::post('/enable', [DatabaseController::class, 'enable'])->name('database.enable');
    Route::post('/disable', [DatabaseController::class, 'disable'])->name('database.disable');
    Route::get('/status', [DatabaseController::class, 'status'])->name('database.status');

    Route::get('/list', [DatabaseController::class, 'list'])->name('database.list');
    Route::post('/create', [DatabaseController::class, 'create'])->name('database.create');
    Route::post('/delete', [DatabaseController::class, 'delete'])->name('database.delete');
    Route::post('/grant', [DatabaseController::class, 'grant'])->name('database.grant');
});
// ssh
Route::prefix('ssh')->group(function () {
    Route::get('/', [SshController::class, 'index'])->name('ssh.index');
    Route::post('/install', [SshController::class, 'install'])->name('ssh.install');
    Route::post('/enable', [SshController::class, 'enable'])->name('ssh.enable');
    Route::post('/disable', [SshController::class, 'disable'])->name('ssh.disable');
    Route::get('/status', [SshController::class, 'status'])->name('ssh.status');
    Route::get('/keys', [SshController::class, 'listKeys'])->name('ssh.listKeys');
    Route::post('/create-key', [SshController::class, 'createKey'])->name('ssh.createKey');
    Route::post('/delete-key', [SshController::class, 'deleteKey'])->name('ssh.deleteKey');
});


Route::prefix('users-groups')->group(function () {
    Route::get('/', [UserGroupController::class, 'index'])->name('users.groups.index');
    Route::get('/list', [UserGroupController::class, 'list'])->name('users.groups.list');
    Route::post('/create-user', [UserGroupController::class, 'createUser'])->name('users.groups.createUser');
    Route::post('/create-group', [UserGroupController::class, 'createGroup'])->name('users.groups.createGroup');
    Route::post('/delete-user', [UserGroupController::class, 'deleteUser'])->name('users.groups.deleteUser');
    Route::post('/delete-group', [UserGroupController::class, 'deleteGroup'])->name('users.groups.deleteGroup');
});

require __DIR__.'/auth.php';

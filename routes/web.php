<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// メイン画面
Route::get('/dashboard', [AppointmentController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 認証が必要な機能
Route::middleware('auth')->group(function () {
    
    // 予約の保存（JSON形式に対応）
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    
    // 予約の削除
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // API: リロードなしでその日の予約を取得する
    Route::get('/api/appointments', function() {
        $date = request('date', date('Y-m-d'));
        return Appointment::whereDate('date', $date)->orderBy('start_time')->get();
    });

    // プロフィール設定
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // routes/web.php の中

// この1行が、Controllerの destroy($id) と繋がるための「道」になります
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

require __DIR__.'/auth.php';
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * 予約一覧を取得
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // ログイン中のユーザーの予約だけを取得
        $appointments = $request->user()->appointments()
                        ->whereDate('date', $date)
                        ->orderBy('start_time', 'asc')
                        ->get();

        // 画面を返す（ここが今のお母様の画面を表示する鍵です）
        return view('dashboard', compact('appointments', 'date'));
    }

    /**
     * 予約の保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required', 
            'duration'    => 'required|integer',
            'service'     => 'nullable|string',
            'price'       => 'nullable|numeric',
        ]);

        $validated['start_time'] = Carbon::parse($request->start_time)->format('H:i:s');

        // ログイン中のユーザーに紐付けて保存
        $request->user()->appointments()->create($validated);

        // ★ JSONではなく、元の画面にリダイレクトして戻す（これが今の画面には必要かも！）
        return redirect()->route('dashboard', ['date' => $request->date])
                         ->with('success', 'Agendamento salvo!');
    }

    /**
     * 削除処理
     */
    public function destroy($id)
{
    // IDから予約を探す
    $appointment = Appointment::findOrFail($id);

    // ★重要：ログイン中のユーザーの予約かチェック
    if ($appointment->user_id !== auth()->id()) {
        return response()->json(['error' => '権限がありません'], 403);
    }

    // 削除実行
    $appointment->delete();

    // 画面に戻る（あるいは成功を返す）
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json(['success' => true]);
    }

    return redirect()->back();
}
}
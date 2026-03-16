<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Userモデルとのリレーションのために追加しておくと安心です
use App\Models\User;

class Appointment extends Model
{
    use HasFactory;

    /**
     * 一括保存（Mass Assignment）を許可する項目
     * user_id を含めて1つにまとめました
     */
    protected $fillable = [
        'user_id',
        'client_name',
        'date',
        'start_time',
        'duration',
        'service',
        'price'
    ];

    /**
     * リレーション設定：Appointmentは一人のUserに所属しています
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
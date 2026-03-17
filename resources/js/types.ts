// 予約データの「型」を定義
export interface Appointment {
    id: number;
    user_name: string;
    appointment_date: string; // カンピーナス時間の日付
    status: 'pending' | 'confirmed' | 'cancelled';
}
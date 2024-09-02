<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fun extends Model
{
    use HasFactory;

    protected $table = 'fun'; // Tên bảng

    // Các trường có thể được gán giá trị hàng loạt
    protected $fillable = [
        'code', 'name', 'birth', 'phone', 'gmail', 'amount', 'time', 'campaignId'
    ];

    // Các trường có kiểu dữ liệu ngày giờ
    protected $dates = [
        'birth', 'time'
    ];

    // Chỉ định kiểu dữ liệu cho các trường
    protected $casts = [
        'campaignId' => 'integer',
        'amount' => 'integer'
    ];

    // Quan hệ với bảng Campaign
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaignId', 'id');
    }
}

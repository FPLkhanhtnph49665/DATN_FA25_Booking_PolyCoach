<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusImage extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong database
     * (Laravel có thể tự hiểu là bus_images, nhưng khai báo sẽ rõ ràng hơn)
     */
    protected $table = 'bus_images';

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'bus_id',
        'image_path',
    ];

    /**
     * Thiết lập quan hệ ngược lại: Một ảnh thuộc về một xe Bus
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    /**
     * Accessor: Tự động trả về URL đầy đủ cho ảnh nếu cần
     * Ví dụ: $image->full_url
     */
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
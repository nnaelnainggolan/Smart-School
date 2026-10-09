<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = ['siswa_id', 'user_id', 'start_date', 'end_date', 'type', 'reason', 'attachment', 'status', 'reviewer_id', 'review_note'];

    protected function casts(): array
    {
        return [];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}

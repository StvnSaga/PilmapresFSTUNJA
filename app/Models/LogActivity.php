<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Ini memungkinkan kita untuk mengambil data user dari sebuah log.
     * Contoh: $log->user->name
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

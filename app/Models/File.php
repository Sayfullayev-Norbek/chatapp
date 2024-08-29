<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'file_type', 'file_size', 'file_url'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'file_id');
    }
}

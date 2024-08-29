<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'users_meta_id', 'file_id', 'message'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function usersMeta()
    {
        return $this->belongsTo(UsersMeta::class, 'users_meta_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

}

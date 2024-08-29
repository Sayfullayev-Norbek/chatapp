<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['users_meta_id', 'subject', 'description', 'status'];

    public function userMeta()
    {
        return $this->belongsTo(UsersMeta::class, 'users_meta_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

}

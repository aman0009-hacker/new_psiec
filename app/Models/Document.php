<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
// use Illuminate\Database\Eloquent\Concerns\HasUuids;


use Encore\Admin\Traits\HasEditable;

class Document extends Model
{
    use HasFactory;

    // public function users()
    // {
    //     return $this->belongsTo(User::class);
    // }
}

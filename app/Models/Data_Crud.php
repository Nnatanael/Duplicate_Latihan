<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
class Data_Crud extends Model
{
    use softDeletes;
    protected $table = 'datadiri';
    protected $fillable = [
        'nama', 'email'
    ];
}

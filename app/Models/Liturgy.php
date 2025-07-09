<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liturgy extends Model
{
    protected $table = 'liturgies';
    use HasFactory;
    protected $fillable = ['day', 'liturgy1', 'liturgy2', 'liturgypsalms', 'liturgygospel'];
}

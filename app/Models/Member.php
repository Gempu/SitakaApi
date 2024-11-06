<?php

namespace App\Models;

use App\Models\Loan;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'member';
    protected $primaryKey = 'member_id';
    protected $fillable = ['member_name', 'email', 'mpasswd', 'member_phone', 'last_login'];
    protected $hidden = ['mpasswd'];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id');
    }
}

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

    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = 'last_update';
    const CREATED_AT = null;

    protected $fillable = ['member_name', 'email', 'mpasswd', 'member_phone', 'last_login', 'score'];
    protected $hidden = ['mpasswd'];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id');
    }
}

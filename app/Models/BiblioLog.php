<?php

namespace App\Models;

use App\Models\Biblio;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiblioLog extends Model
{
    use HasFactory;

    protected $table = 'biblio_log';
    protected $primaryKey = 'biblio_log_id';
    protected $fillable = ['biblio_id', 'member_id', 'action', 'date'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }
}

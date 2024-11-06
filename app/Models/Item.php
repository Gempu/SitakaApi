<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\Biblio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';
    protected $primaryKey = 'item_id';
    protected $fillable = ['biblio_id', 'call_number', 'item_code', 'item_status_id'];

    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class, 'item_code', 'item_code');
    }
}

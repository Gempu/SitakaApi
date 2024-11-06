<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Biblio extends Model
{
    use HasFactory;

    protected $table = 'biblio';
    protected $primaryKey = 'biblio_id';
    protected $fillable = ['title', 'publish_year', 'collation', 'notes', 'image'];

    public function items()
    {
        return $this->hasMany(Item::class, 'biblio_id', 'biblio_id');
    }

    public function loans()
    {
        return $this->hasManyThrough(Loan::class, Item::class, 'biblio_id', 'item_code', 'biblio_id', 'item_code');
    }
}

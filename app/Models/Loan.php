<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loan';
    protected $primaryKey = 'loan_id';
    protected $fillable = ['item_code', 'member_id', 'loan_date', 'due_date', 'return_date'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}

<?php

namespace App\Models;

use App\Models\Common\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    public $table = "inventories";
    protected $guarded = [];
    public function item() {
        return $this->belongsTo(Item::class);
    }
}

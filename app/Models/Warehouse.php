<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['warehouse'];

    /**
     * Get the inventory for the warehouse.
    */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['warehouse_id', 'inventoryable_id', 'inventoryable_type','amount'];
    

        /**
     * Get the brand that owns the type.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the parent codeable model.
     */
    public function inventoryable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all of the payments that are assigned this code.
     */
    public function payments(): MorphToMany
    {
        return $this->morphToMany(Payment::class, 'inventoryale');
    }

        /**
     * Get all of the codes of this payment.
     */
    public function codes_of_inventory(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }

    /**
     * Get all of the brands that are assigned this payment.
     */
    public function codes(): MorphToMany
    {
        return $this->morphedByMany(Code::class, 'inventoryale');
    }
}

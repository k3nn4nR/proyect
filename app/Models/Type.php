<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Type extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['type','brand_id'];

    /**
     * Get all of the tags that are assigned this type.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the codes type.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }

    /**
     * Get the brand that owns the type.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all of the payments that are assigned this type.
     */
    public function payments(): MorphToMany
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }

        /**
     * Get all of the codes type.
     */
    public function inventories(): MorphMany
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['brand'];

    /**
     * Get all of the tags that are assigned this brand.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the codes of this brand.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }

    /**
     * Get the types for the brand.
     */
    public function types(): HasMany
    {
        return $this->hasMany(Type::class);
    }
}

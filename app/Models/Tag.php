<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tag'];

    /**
     * Get all of the items that are assigned this tag.
     */
    public function items(): MorphToMany
    {
        return $this->morphedByMany(Item::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }
 
    /**
     * Get all of the brands that are assigned this tag.
     */
    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the currencies that are assigned this tag.
     */
    public function currencies(): MorphToMany
    {
        return $this->morphedByMany(Currency::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the companies that are assigned this tag.
     */
    public function companies(): MorphToMany
    {
        return $this->morphedByMany(Company::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the services that are assigned this tag.
     */
    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the services that are assigned this tag.
     */
    public function codes(): MorphToMany
    {
        return $this->morphedByMany(Code::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the payments that are assigned this tag.
     */
    public function payments(): MorphToMany
    {
        return $this->morphedByMany(Payment::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the types that are assigned this tag.
     */
    public function types(): MorphToMany
    {
        return $this->morphedByMany(Type::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all tag childs that are assigned this tag.
     */
    public function tags(): MorphToMany
    {
        return $this->morphedByMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
        
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['company'];

    /**
     * Get the payments for the company.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all of the tags that are assigned this company.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the codes of this company.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }
}

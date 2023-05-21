<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Currency extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['currency'];

        /**
     * Get the payments for the currency.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all of the tags that are assigned this currency.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all of the codes of this currency.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }
}

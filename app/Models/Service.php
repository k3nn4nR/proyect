<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['service'];
    
    /**
     * Get all of the tags that are assigned this service.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the codes of this service.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }

    /**
     * Get all of the payments that are assigned this service.
     */
    public function payments(): MorphToMany
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}

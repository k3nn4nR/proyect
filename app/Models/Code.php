<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Code extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['code', 'codeable_id', 'codeable_type'];

    /**
     * Get all of the tags that are assigned this tag.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the parent codeable model.
     */
    public function codeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all of the payments that are assigned this code.
     */
    public function payments(): MorphToMany
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}

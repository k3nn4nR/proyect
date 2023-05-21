<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['item'];

    /**
     * Get all of the tags that are assigned this item.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all of the codes of this item.
     */
    public function codes(): MorphMany
    {
        return $this->morphMany(Code::class, 'commentable');
    }
}

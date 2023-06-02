<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','currency_id','total'];

    /**
     * Get the company that owns the payment.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the currency that owns the payment.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get all of the tags that are assigned this payment.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->wherePivotNull('deleted_at');
    }

    /**
     * Get all of the codes of this payment.
     */
    public function codes_of_payment(): MorphMany
    {
        return $this->morphMany(Code::class, 'codeable');
    }

    /**
     * Get all of the brands that are assigned this payment.
     */
    public function types(): MorphToMany
    {
        return $this->morphedByMany(Type::class, 'paymentable')->withPivot('amount','price','subtotal');
    }

    /**
     * Get all of the brands that are assigned this payment.
     */
    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'paymentable')->withPivot('amount','price','subtotal');
    }

    /**
     * Get all of the brands that are assigned this payment.
     */
    public function items(): MorphToMany
    {
        return $this->morphedByMany(Item::class, 'paymentable')->withPivot('amount','price','subtotal');
    }

    /**
     * Get all of the brands that are assigned this payment.
     */
    public function codes_paid(): MorphToMany
    {
        return $this->morphedByMany(Code::class, 'paymentable')->withPivot('amount','price','subtotal');
    }
}

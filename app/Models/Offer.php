<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    protected $fillable = [
        'offer_id',
        'provider',
        'title',
        'description',
        'instructions',
        'requirements',
        'image',
        'link',
        'points',
        'payout',
        'categories',
        'countries',
        'devices',
        'events',
    ];

    protected $casts = [
        'instructions' => 'array',
        'categories' => 'array',
        'countries' => 'array',
        'devices' => 'array',
        'events' => 'array',
    ];


    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'offer_id', 'offer_id');
    }

}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'name', 'user_id'];

    public function pagespeedResults(): HasMany
    {
        return $this->hasMany(PagespeedResult::class);
    }

    public function latestPagespeedResult(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        // ofMany met closure is de correcte manier om een where-filter te combineren met latestOfMany
        return $this->hasOne(PagespeedResult::class)->ofMany(
            ['id' => 'max'],
            fn($q) => $q->where('strategy', 'desktop')
        );
    }
}

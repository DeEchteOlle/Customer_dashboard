<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [ 'url' , 'name'];

    public function pagespeedResults(): HasMany
    {
        return $this->hasMany(PagespeedResult::class);
    }
}

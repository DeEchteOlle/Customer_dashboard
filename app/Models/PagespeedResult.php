<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagespeedResult extends Model
{
    use HasFactory;

    protected $fillable = ['lcp', 'inp', 'cls', 'fcp', 'ttfb', 'website_id'];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

}

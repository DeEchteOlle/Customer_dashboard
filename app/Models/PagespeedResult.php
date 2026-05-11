<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagespeedResult extends Model
{
    use HasFactory;

    protected $fillable = ['lcp', 'inp', 'cls', 'fcp', 'ttfb', 'website_id', 'strategy'];

    // Geeft een score 0-100: 100 = alle metrics goed, 0 = alles slecht
    public function healthScore(): ?int
    {
        $checks = [
            [$this->lcp,  2.5,  4.0],
            [$this->inp,  200,  500],
            [$this->cls,  0.1,  0.25],
            [$this->fcp,  1.8,  3.0],
            [$this->ttfb, 0.6,  1.8],
        ];

        $scores = [];
        foreach ($checks as [$value, $good, $poor]) {
            if ($value === null) continue;
            $scores[] = $value <= $good ? 100 : ($value <= $poor ? 50 : 0);
        }

        return empty($scores) ? null : (int) round(array_sum($scores) / count($scores));
    }

    public function website(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

}

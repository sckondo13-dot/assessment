<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'evaluation_deadline',
        'sort_order',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(SiteMember::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}

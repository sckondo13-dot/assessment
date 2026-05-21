<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_id',
        'evaluator_user_id',
        'target_user_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * 現場
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * 評価者
     */
    public function evaluator()
    {
        return $this->belongsTo(
            User::class,
            'evaluator_user_id'
        );
    }

    /**
     * 被評価者
     */
    public function targetUser()
    {
        return $this->belongsTo(
            User::class,
            'target_user_id'
        );
    }

    /**
     * 回答一覧
     */
    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }
}

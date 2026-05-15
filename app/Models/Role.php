<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    public function siteMembers(): HasMany
    {
        return $this->hasMany(SiteMember::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'target_role_id');
    }
}

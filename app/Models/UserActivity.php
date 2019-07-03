<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserActivity extends Model
{
    protected $fillable = [
        'entity_type', 'entity_id', 'field_name', 'old_value', 'new_value', 'modified_by',
    ];

    /**
     * Polymorphic relation to user table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}

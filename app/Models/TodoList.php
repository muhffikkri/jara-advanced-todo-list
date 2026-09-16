<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TodoList extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    /**
     * Get the user that owns the list.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the list.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user', 'list_id', 'user_id')
                    ->withPivot('added_by')
                    ->withTimestamps();
    }
}

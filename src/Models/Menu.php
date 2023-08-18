<?php

namespace Codedor\FilamentMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $identifier
 */
class Menu extends Model
{
    protected $fillable = [
        'working_title',
        'identifier',
        'description',
        'depth',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereDoesntHave('parent')
            ->ordered();
    }
}

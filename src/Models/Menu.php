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

    public function hasItemsWithDepthIssues(): bool
    {
        return MenuItem::where('menu_id', $this->id)
            ->with('parent')
            ->get()
            ->filter(function (MenuItem $item) {
                $depth = 0;
                $parent = $item->parent;

                while ($parent) {
                    $parent = $parent->parent;
                    $depth++;
                }

                return $depth >= $this->depth;
            })
            ->isNotEmpty();
    }
}

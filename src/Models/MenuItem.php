<?php

namespace Codedor\FilamentMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property string $working_title
 * @property string|array $link
 * @property string|array $translated_link
 * @property string $label
 * @property int $parent_id
 * @property int $menu_id
 * @property bool $online
 */
class MenuItem extends Model
{
    use SortableTrait;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'sort_order',
        'working_title',
        'type',
        'data',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public $with = [
        'children',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('sort_order');
    }
}

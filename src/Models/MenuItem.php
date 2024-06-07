<?php

namespace Codedor\FilamentMenu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function type(): Attribute
    {
        return Attribute::make(
            get: fn (null | string $value) => config("filament-menu.navigation-elements.{$value}"),
            set: fn (null | string $value) => array_search($value, config('filament-menu.navigation-elements')),
        );
    }

    public function onlineValues(): array
    {
        return (new $this->type)->locales($this->data);
    }
}

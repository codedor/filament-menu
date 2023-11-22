<?php

namespace Codedor\FilamentMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

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
    use HasTranslations;
    use SortableTrait;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'sort_order',
        'working_title',
        'link',
        'label',
        'translated_link',
        'online',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $translatable = [
        'label',
        'translated_link',
        'online',
    ];

    public $with = [
        'children',
    ];

    public $casts = [
        'link' => 'json',
        'online' => 'boolean',
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

    public function getRouteAttribute(): HtmlString|string|null
    {
        try {
            if (! empty($this->translated_link)) {
                return lroute($this->translated_link) ?? '#';
            }

            return lroute($this->link) ?? '#';
        } catch (\Throwable $e) {
            return '#';
        }
    }
}

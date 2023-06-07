<?php

namespace Codedor\FilamentMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Navigation\Navigation;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasTranslations;

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

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function getRouteAttribute(): string
    {
        if (! empty($this->translated_link)) {
            return lroute($this->translated_link) ?? '#';
        }

        return lroute($this->link) ?? '#';
    }
}

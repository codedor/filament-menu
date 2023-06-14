<?php

namespace Codedor\FilamentMenu\Filament\Pages;

use Closure;
use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Codedor\FilamentMenu\Models\Menu;
use Codedor\FilamentMenu\Models\MenuItem;
use Codedor\LinkPicker\Forms\Components\LinkPickerInput;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Codedor\TranslatableTabs\Resources\Traits\HasTranslations;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class MenuBuilder extends Page
{
    use Concerns\InteractsWithRecord;
    use HasTranslations;

    protected static string $resource = MenuResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-menu::filament.pages.menu-builder';

    public ?MenuItem $editingMenuItem = null;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public static function route(string $path): array
    {
        return [
            'class' => static::class,
            'route' => $path,
        ];
    }

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function createMenuItem()
    {
        $this->record->items()->create([
            'working_title' => 'New Menu Item',
            'sort_order' => $this->record->items()->count() + 1001,
        ]);

        $this->record->refresh();
    }

    public function setEditingMenuItem(?int $id = null)
    {
        $this->editingMenuItem = $id ? MenuItem::find($id) : new MenuItem();

        $this->form->fill([
            'working_title' => $this->editingMenuItem->working_title,
            'link' => is_string($this->editingMenuItem->link)
                ? json_decode($this->editingMenuItem->link ?? '[]', true)
                : $this->editingMenuItem->link,
            ...LocaleCollection::mapWithKeys(function (Locale $locale) {
                $locale = $locale->locale();

                $this->editingMenuItem->setLocale($locale);

                return [$locale => [
                    'label' => $this->editingMenuItem->label,
                    'translated_link' => $this->editingMenuItem->translated_link,
                    'online' => $this->editingMenuItem->online,
                ]];
            }),
        ]);
    }

    public function removeItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);

        MenuItem::where('parent_id', $menuItem->id)
            ->update([
                'parent_id' => null,
            ]);

        $menuItem->delete();

        Notification::make()
            ->title(__('filament-menu::menu-item.deleted'))
            ->success()
            ->send();

        $this->record->refresh();
    }

    public function submitEditForm()
    {
        $this->validate();

        $data = $this->mutateFormDataBeforeSave($this->form->getState());

        if ($this->editingMenuItem->id) {
            $this->editingMenuItem->update($data);

            $title = __('filament-menu::menu-item.successfully updated');
        } else {
            $data['menu_id'] = $this->record->id;
            $this->editingMenuItem->create($data);

            $title = __('filament-menu::menu-item.successfully created');
        }

        Notification::make()
            ->title($title)
            ->success()
            ->send();

        $this->record->refresh();

        $this->dispatchBrowserEvent('close-modal', [
            'id' => 'filament-menu::edit-menu-item-modal',
        ]);
    }

    public function handleNewOrder(string $statePath, array $items)
    {
        $itemIds = collect($items)->map(fn ($item) => Str::afterLast($item, '.'));

        MenuItem::whereIn('id', $itemIds)
            ->update([
                'parent_id' => ($statePath === 'data.items') ? null : Str::afterLast($statePath, '.'),
            ]);

        MenuItem::setNewOrder($itemIds, 1000);

        Notification::make()
            ->title(__('filament-menu::menu-item.sorted'))
            ->success()
            ->send();

        $this->record->refresh();
    }

    protected function getFormSchema(): array
    {
        return [
            TranslatableTabs::make('Translations')
                ->columnSpan(['lg' => 2])
                ->defaultFields([
                    TextInput::make('working_title')
                        ->required()
                        ->maxLength(255),

                    LinkPickerInput::make('link'),
                ])
                ->translatableFields([
                    TextInput::make('label')
                        ->label('Label')
                        ->required(fn (Closure $get) => $get('online')),

                    LinkPickerInput::make('translated_link')
                        ->label('Link')
                        ->helperText('If you want to override the link for this translation, you can do so here.'),

                    Toggle::make('online')
                        ->label('Online'),
                ]),
        ];
    }

    public static function getResource(): string
    {
        return static::$resource;
    }

    public static function getModel(): string
    {
        return MenuItem::class;
    }

    protected function resolveRecord($key): Model
    {
        $record = static::getResource()::resolveRecordRouteBinding($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel(Menu::class, [$key]);
        }

        return $record;
    }
}

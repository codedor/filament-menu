<?php

namespace Codedor\FilamentMenu\Filament\Pages;

use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Codedor\FilamentMenu\Models\Menu;
use Codedor\FilamentMenu\Models\MenuItem;
use Codedor\LinkPicker\Filament\LinkPickerInput;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Codedor\TranslatableTabs\Resources\Traits\HasTranslations;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Pages\Concerns;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class MenuBuilder extends Page
{
    use Concerns\InteractsWithRecord;
    use HasTranslations;

    protected static string $resource = MenuResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament-menu::filament.pages.menu-builder';

    public bool $isEditingMenuItem = false;

    public string|null $editingMenuItemId = null;
    public string|null $editingMenuItemWorkingTitle = null;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function addAction(): Action
    {
        return $this->formAction()
            ->label(__('filament-menu.add menu item'))
            ->size('sm')
            ->button();
    }

    public function editAction(): Action
    {
        return $this->formAction()
            ->icon('heroicon-o-pencil')
            ->label(__('filament-menu.edit menu item'))
            ->size('sm')
            ->link();
    }

    public function formAction(): Action
    {
        return Action::make('edit')
            ->fillForm(function (array $arguments, array $data) {
                $menuItem = isset($arguments['menuItem']) ? MenuItem::find($arguments['menuItem']) : new MenuItem();

                return [
                    'working_title' => $menuItem->working_title,
                    'link' => is_string($menuItem->link)
                        ? json_decode($menuItem->link ?? '[]', true)
                        : $menuItem->link,
                    ...LocaleCollection::mapWithKeys(function (Locale $locale) use ($menuItem) {
                        $locale = $locale->locale();

                        $menuItem->setLocale($locale);

                        return [
                            $locale => [
                                'label' => $menuItem->label,
                                'translated_link' => $menuItem->translated_link,
                                'online' => $menuItem->online,
                            ],
                        ];
                    }),
                ];
            })
            ->form([
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
                            ->required(fn (Get $get) => $get('online')),

                        LinkPickerInput::make('translated_link')
                            ->label('Link')
                            ->helperText('If you want to override the link for this translation, you can do so here.'),

                        Toggle::make('online')
                            ->label('Online'),
                    ]),
            ])
            ->action(function (array $arguments, array $data) {
                $data['menu_id'] = $this->record->id;

                $menuItem = MenuItem::updateOrCreate(
                    [
                        'id' => $arguments['menuItem'] ?? null,
                    ],
                    $this->mutateFormDataBeforeSave($data),
                );

                $title = $menuItem->wasRecentlyCreated
                    ? __('filament-menu::menu-item.successfully created')
                    : __('filament-menu::menu-item.successfully updated');

                Notification::make()
                    ->title($title)
                    ->success()
                    ->send();

                $this->record->refresh();
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-o-trash')
            ->label(__('filament-menu.delete menu item'))
            ->size('sm')
            ->color('danger')
            ->link()
            ->requiresConfirmation()
            ->action(function (array $arguments, array $data) {
                $menuItem = MenuItem::findOrFail($arguments['menuItem'] ?? null);

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
            });
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

    public static function getResource(): string
    {
        return static::$resource;
    }

    public function getModel(): string
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

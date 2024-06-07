<?php

namespace Codedor\FilamentMenu\Filament\Pages;

use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Codedor\FilamentMenu\Models\Menu;
use Codedor\FilamentMenu\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class MenuBuilder extends Page
{
    use Concerns\InteractsWithRecord;

    protected static string $resource = MenuResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament-menu::filament.pages.menu-builder';

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
            ->label(__('filament-menu::menu-builder.add menu item'))
            ->size('sm')
            ->button();
    }

    public function editAction(): Action
    {
        return $this->formAction()
            ->icon('heroicon-o-pencil')
            ->label(__('filament-menu::menu-builder.edit menu item'))
            ->size('sm')
            ->link();
    }

    public function formAction(): Action
    {
        $types = collect(config('filament-menu.navigation-elements', []))
            ->mapWithKeys(fn (string $element) => [$element => $element::name()]);

        return Action::make('edit')
            ->fillForm(function (array $arguments) {
                $menuItem = isset($arguments['menuItem'])
                    ? MenuItem::find($arguments['menuItem'])
                    : new MenuItem;

                return [
                    'working_title' => $menuItem->working_title,
                    'type' => $menuItem->type,
                    ...$menuItem->data ?? [],
                ];
            })
            ->form(fn () => [
                TextInput::make('working_title')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->options($types)
                    ->required()
                    ->reactive(),

                Grid::make(1)
                    ->hidden(fn (Get $get) => empty($get('type')))
                    ->schema(fn (Get $get) => $get('type') ? $get('type')::make()->schema() : []),
            ])
            ->action(function (array $arguments, array $data) {
                $menuItem = MenuItem::updateOrCreate([
                    'id' => $arguments['menuItem'] ?? null,
                    'menu_id' => $this->record->id,
                ], [
                    'working_title' => $data['working_title'],
                    'type' => $data['type'],
                    'data' => collect($data)->except('type', 'working_title'),
                ]);

                $title = $menuItem->wasRecentlyCreated
                    ? __('filament-menu::menu-builder.successfully created')
                    : __('filament-menu::menu-builder.successfully updated');

                Notification::make()->title($title)->success()->send();

                $this->record->refresh();
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-o-trash')
            ->label(__('filament-menu::menu-builder.delete menu item'))
            ->size('sm')
            ->color('danger')
            ->link()
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $menuItem = MenuItem::findOrFail($arguments['menuItem'] ?? null);

                MenuItem::where('parent_id', $menuItem->id)->update([
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

        MenuItem::whereIn('id', $itemIds)->update([
            'parent_id' => ($statePath === 'data.items') ? null : Str::afterLast($statePath, '.'),
        ]);

        MenuItem::setNewOrder($itemIds, 1000);

        Notification::make()
            ->title(__('filament-menu::menu-builder.successfully sorted'))
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

    protected function resolveRecord($key): Menu
    {
        $record = static::getResource()::resolveRecordRouteBinding($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel(Menu::class, [$key]);
        }

        return $record;
    }
}

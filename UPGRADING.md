# Upgrading

## From v1 to v2

This major release introduces a completely refactored menu system with a more flexible and extensible architecture. The main change is the introduction of **Navigation Elements** - a new way to handle different types of menu items that allows for better customization and future extensibility.

### Breaking Changes ⚠️

#### Database Schema Changes
The menu items table structure has been completely redesigned:
- **Removed columns**: `link`, `label`, `translated_link`, `online`
- **Added columns**: `type` (string), `data` (JSON)

All menu item data is now stored in a flexible JSON structure, allowing for different types of menu items with varying data requirements.

#### Model Changes
The `MenuItem` model has been significantly simplified:
- Removed `HasTranslations` and `HasOnlineScope` traits
- Removed translatable properties
- Added `type` attribute that maps to navigation element classes
- All item-specific data is now stored in the `data` JSON column

### Migration Guide

1. **Run the migration**: The package includes an automatic migration that will:
    - Add the new `type` and `data` columns
    - Migrate all existing menu items to the new structure
    - Remove the old columns

2. **Update custom views**: If you have custom menu rendering views, update them to use the new structure:
   ```blade
   // Old way
   {{ $item->label }}
   {{ $item->route }}
   
   // New way
   {{ (new $item->type)->render($item) }}
   ```

3. **Update any custom code**: If you interact with menu items programmatically, update to use the new data structure:
   ```php
   // Old way
   $menuItem->label;
   $menuItem->getTranslation('online', 'en');
   
   // New way
   $menuItem->data['en']['label'];
   $menuItem->data['en']['online'];
   ```

### Data Structure Changes

Menu item data is now stored as:
```json
{
  "link": "main-link-value",
  "en": {
    "label": "English Label",
    "online": true,
    "translated_link": "optional-english-specific-link"
  },
  "nl": {
    "label": "Dutch Label",
    "online": false,
    "translated_link": null
  }
}
```

### Upgrade Path

1. **Backup your database** before upgrading
2. Run `composer update codedor/filament-menu`
3. Run `php artisan migrate`
4. Clear caches: `php artisan cache:clear`
5. Check the blade components in your project, they have to be updated to use the new navigation element system
6. Test your menus in both the admin panel and front-end

### Troubleshooting

If you encounter issues during the upgrade:

1. **Migration fails**: Check that you have a backup and that no custom code is interfering with the migration
2. **Menu items not displaying**: Verify that your custom views have been updated to use the new navigation element system
3. **Missing data**: The migration should preserve all existing data, but check the `data` column in the `menu_items` table to ensure everything migrated correctly

For additional support, please open an issue on GitHub.

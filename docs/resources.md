# Resource helpers

Two traits for the app's base Nova resource. They are opt-in, so a package update never changes resources that don't use them.

```php
use Igne\Nova\Resources\Concerns\IgnoresUnknownSortColumns;
use Igne\Nova\Resources\Concerns\TranslatesLabels;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource
{
    use IgnoresUnknownSortColumns;
    use TranslatesLabels;
}
```

## Ignore unknown sort columns

Nova sorts an index on the `orderBy` the browser sends, without checking it against the sortable fields. A bookmarked link that sorts on a field that is no longer sortable, or on a relation, gives a SQL error and an empty index. `IgnoresUnknownSortColumns` drops a sort on anything that is not a column of the resource's table, and shows the index in its default order.

Columns are read once per table per process. A resource that sorts on a column a join or sub-select adds can't use the trait, because such a column is not on the table.

## Translated labels

`TranslatesLabels` takes the resource's labels from the app's translations, keyed by its URI key, in `lang/{locale}/nova.php`:

```php
return [
    'resources' => [
        'users' => ['plural' => 'Gebruikers', 'singular' => 'Gebruiker'],
        'invitations' => ['plural' => 'Uitnodigingen', 'singular' => 'Uitnodiging'],
    ],
];
```

A missing key falls back to the label Nova generates from the class name, so a half-translated app still renders. Renaming a resource's `uriKey()` moves its translation key with it.

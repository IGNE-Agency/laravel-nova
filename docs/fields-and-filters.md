# Fields and filters

## EnumBadge

`Igne\Nova\Fields\EnumBadge` is Nova's Badge field for an enum attribute. The enum gives every case its label and colour, so no case can be missing from the mapping, which makes Nova's Badge throw when the page renders.

```php
EnumBadge::make(__('Status'), 'status', InvitationStatus::class),
```

The attribute can also be a callable; it returns the case's value.

### Options

The enum implements `Igne\Nova\Fields\Contracts\BadgeEnum`, which only asks for a label. Every other option is an interface on the enum; leave it out and its default applies.

| Option | Add to the enum | Without it |
|--------|-----------------|------------|
| Label | `BadgeEnum`, `label()` (required) | — |
| Label from the translations | `use TranslatesBadgeLabel;` instead of writing `label()` | — |
| One of Nova's colours per case | `HasBadgeType`, `badgeType()` returns a `BadgeType` | Every case in the info colour |
| Own CSS classes per case | `HasBadgeClasses`, `badgeClasses()`; wins over `HasBadgeType` | Nova's colours |

```php
use Igne\Nova\BadgeType;
use Igne\Nova\Fields\Concerns\TranslatesBadgeLabel;
use Igne\Nova\Fields\Contracts\BadgeEnum;
use Igne\Nova\Fields\Contracts\HasBadgeType;

enum InvitationStatus: string implements BadgeEnum, HasBadgeType
{
    use TranslatesBadgeLabel;

    case PENDING = 'pending';
    case ACCEPTED = 'accepted';

    public function badgeType(): BadgeType
    {
        return match ($this) {
            self::PENDING => BadgeType::INFO,
            self::ACCEPTED => BadgeType::SUCCESS,
        };
    }
}
```

`TranslatesBadgeLabel` reads the key `enums.{enum in snake case}.{value}`, here `enums.invitation_status.pending`, so the app's `lang/en/enums.php` holds:

```php
return [
    'invitation_status' => [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
    ],
];
```

`label()` has the same signature as `HasLabel` in igne-agency/laravel-permissions, so a role or space enum can implement both.

For brand colours instead of Nova's four, `badgeClasses()` returns the classes:

```php
public function badgeClasses(): string
{
    return 'text-brand-700 bg-brand-100';
}
```

Load the CSS for such classes with the package's [assets](panel.md#assets).

### Empty and unknown values

An empty value shows "Unknown" in the info colour, so the badge can back a nullable column, and the field's filter lists every case and "Unknown".

A value that is not a case, such as a row still holding a case the enum has since dropped, throws an `EnumBadgeException` that names the enum and its cases. Add the case back or migrate those rows. An enum that does not implement `BadgeEnum` throws the same exception when the field is made.

## CreatedWithin

`Igne\Nova\Filters\CreatedWithin` filters records on a timestamp within the last days: "Today", "Last 7 days" and "Last 30 days" by default. "Last 7 days" is today and the six days before it.

```php
public function filters(NovaRequest $request): array
{
    return [
        new CreatedWithin,                                       // created_at, today / 7 / 30 days
        new CreatedWithin('sent_at', [7, 14, 30], __('Sent')),   // another column, windows and name
    ];
}
```

Its key includes the column, so one resource can filter on several columns with it. For a from–to range, use Nova's own filter on a date field: `Date::make(__('Created at'), 'created_at')->filterable()`.

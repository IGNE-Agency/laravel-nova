# Fields and filters

## EnumBadge

`Igne\Nova\Fields\EnumBadge` is Nova's Badge field for an enum attribute. The enum gives every case its label and colour, so no case can be missing from the mapping, which makes Nova's Badge throw when the page renders.

The enum implements `Igne\Nova\Fields\Contracts\BadgeEnum`:

```php
use Igne\Nova\BadgeType;
use Igne\Nova\Fields\Contracts\BadgeEnum;

enum InvitationStatus: string implements BadgeEnum
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';

    public function label(): string
    {
        return __("invitations.status.{$this->value}");
    }

    public function badgeType(): BadgeType
    {
        return match ($this) {
            self::PENDING => BadgeType::INFO,
            self::ACCEPTED => BadgeType::SUCCESS,
        };
    }
}
```

```php
EnumBadge::make(__('Status'), 'status', InvitationStatus::class),
```

The attribute can also be a callable; it returns the case's value. An empty value shows "Unknown" in the info colour, so the badge can back a nullable column, and the field's filter lists every case and "Unknown".

For brand colours instead of Nova's four, also implement `Igne\Nova\Fields\Contracts\HasBadgeClasses`; its classes win over the badge type:

```php
public function badgeClasses(): string
{
    return 'text-brand-700 bg-brand-100';
}
```

Load the CSS for such classes with the package's [assets](panel.md#assets).

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

<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Concerns;

use Igne\Nova\Fields\Contracts\BadgeEnum;
use Illuminate\Support\Str;

/**
 * A badge enum's label() from the app's translations, keyed by the enum and
 * the case's value: InvitationStatus::PENDING reads
 * "enums.invitation_status.pending".
 *
 * @phpstan-require-implements BadgeEnum
 */
trait TranslatesBadgeLabel
{
    public function label(): string
    {
        $key = \sprintf('enums.%s.%s', Str::snake(class_basename(self::class)), $this->value);
        $label = __($key);

        return \is_string($label) ? $label : $key;
    }
}

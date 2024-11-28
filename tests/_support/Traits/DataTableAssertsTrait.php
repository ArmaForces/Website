<?php

declare(strict_types=1);

namespace App\Tests\Traits;

trait DataTableAssertsTrait
{
    public function checkTableRowCheckbox(string $value): void
    {
        $this->checkOption(sprintf('[value="%s"]', $value));
    }

    public function uncheckTableRowCheckbox(string $value): void
    {
        $this->uncheckOption(sprintf('[value="%s"]', $value));
    }

    public function seeTableRowCheckboxesAreChecked(array $values = []): void
    {
        $valueSelector = array_map(fn (string $value) => sprintf('[value="%s"]', $value), $values);
        $checkboxSelector = implode(', ', $valueSelector);

        $this->seeCheckboxIsChecked($checkboxSelector);
    }

    public function seeTableRowCheckboxesAreUnchecked(string $idPrefix, array $valuesToExclude = []): void
    {
        $checkboxSelector = sprintf('[id^="%s"]', $idPrefix);
        if ($valuesToExclude) {
            $valueSelector = array_map(fn (string $value) => sprintf(':not([value^="%s"])', $value), $valuesToExclude);
            $checkboxSelector .= implode('', $valueSelector);
        }

        $this->dontSeeCheckboxIsChecked($checkboxSelector);
    }
}

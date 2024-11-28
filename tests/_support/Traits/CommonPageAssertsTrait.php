<?php

declare(strict_types=1);

namespace App\Tests\Traits;

trait CommonPageAssertsTrait
{
    public function seePageNavbarAsUnauthenticatedUser(): void
    {
        $this->seeCommonNavbarElements();

        $this->dontSeeLink('Manage mods');
        $this->dontSeeLink('Mods');
        $this->dontSeeLink('Mod groups');
        $this->dontSeeLink('DLCs');
        $this->dontSeeLink('Mod lists');

        $this->dontSeeLink('Manage users');
        $this->dontSeeLink('Users');
        $this->dontSeeLink('User groups');

        $this->dontSeeElement('a[href="ts3server://ts.localhost.com?password=test"]');

        $this->seeLink('Login');
        $this->dontSeeLink('Logout');
    }

    public function seePageNavbarAsAuthenticatedUser(): void
    {
        $this->seeCommonNavbarElements();

        $this->dontSeeLink('Manage mods');
        $this->dontSeeLink('Mods');
        $this->dontSeeLink('Mod groups');
        $this->dontSeeLink('DLCs');
        $this->dontSeeLink('Mod lists');

        $this->dontSeeLink('Manage users');
        $this->dontSeeLink('Users');
        $this->dontSeeLink('User groups');

        $this->seeElement('a[href="ts3server://ts.localhost.com?password=test"]');

        $this->dontSeeLink('Login');
        $this->seeLink('Logout');
    }

    public function seePageNavbarAsAdmin(): void
    {
        $this->seeCommonNavbarElements();

        $this->seeLink('Manage mods');
        $this->seeLink('Mods');
        $this->seeLink('Mod groups');
        $this->seeLink('DLCs');
        $this->seeLink('Mod lists');

        $this->seeElement('a[href="ts3server://ts.localhost.com?password=test"]');

        $this->seeLink('Manage users');
        $this->seeLink('Users');
        $this->seeLink('User groups');
    }

    public function seeActionButton(string $tooltip, string $url = null): void
    {
        $selector = sprintf('a i[title="%s"]', $tooltip);
        if ($url) {
            $selector = sprintf('a[href="%s"] i[title="%s"]', $url, $tooltip);
        }
        $this->seeElement($selector);
    }

    public function dontSeeActionButton(string $tooltip, string $url = null): void
    {
        $selector = sprintf('a i[title="%s"]', $tooltip);
        if ($url) {
            $selector = sprintf('a[href="%s"] i[title="%s"]', $url, $tooltip);
        }
        $this->dontSeeElement($selector);
    }

    public function seePageFooter(): void
    {
        $this->seeLink('Statute', 'https://drive.google.com/file/d/1a2Ote4EegVOxYUP7Un9gosLzBSmWV8fj');
        $this->seeLink('Calendar', 'https://docs.google.com/spreadsheets/d/1t1158AsoxIwXI5FlPNjqbaXk6Cx7oq7Ocgchnsk2_TE');
        $this->seeLink('Wiki', 'https://wiki.armaforces.com');
    }

    private function seeCommonNavbarElements(): void
    {
        $this->seeLink('Homepage');
        $this->seeLink('Missions');
        $this->seeLink('Get mods');

        $this->seeElement('a[href="https://github.com/ArmaForces"]');
        $this->seeElement('a[href="https://discord.gg/wcuVSrU"]');
        $this->seeElement('a[href="https://steamcommunity.com/id/armaforces/myworkshopfiles?appid=107410"]');

        $this->seeLink('Statute', 'https://drive.google.com/file/d/1a2Ote4EegVOxYUP7Un9gosLzBSmWV8fj');
        $this->seeLink('Calendar', 'https://docs.google.com/spreadsheets/d/1t1158AsoxIwXI5FlPNjqbaXk6Cx7oq7Ocgchnsk2_TE');
        $this->seeLink('Wiki', 'https://wiki.armaforces.com');
    }
}

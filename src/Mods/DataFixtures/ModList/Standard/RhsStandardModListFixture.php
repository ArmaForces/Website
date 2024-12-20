<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\ModList\Standard;

use App\Mods\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\Mods\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\Mods\DataFixtures\Mod\Directory\Deprecated\R3ModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Disabled\ArmaForcesJbadBuildingFixModFixture;
use App\Mods\DataFixtures\ModGroup\RhsModGroupFixture;
use App\Mods\Entity\ModList\StandardModList;
use App\Shared\Test\Traits\TimeTrait;
use App\Users\DataFixtures\User\User2Fixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsStandardModListFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = 'c3b11c2f-9254-4262-bfde-3605df0149d4';
    public const NAME = 'RHS';
    public const OWNER_ID = User2Fixture::ID;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $owner = $this->getReference(self::OWNER_ID);

            $standardModList = new StandardModList(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                [
                    $this->getReference(R3ModFixture::ID),
                    $this->getReference(ArmaScriptProfilerModFixture::ID),

                    $this->getReference(AceInteractionMenuExpansionModFixture::ID),
                    $this->getReference(ArmaForcesAceMedicalModFixture::ID),
                    $this->getReference(LegacyArmaForcesModsModFixture::ID),
                    $this->getReference(ArmaForcesJbadBuildingFixModFixture::ID),
                    $this->getReference(ArmaForcesMedicalModFixture::ID),
                ],
                [
                    $this->getReference(RhsModGroupFixture::ID),
                ],
                [
                    $this->getReference(CslaIronCurtainDlcFixture::ID),
                ],
                $owner,
                false,
                false,
            );

            $standardModList->created($owner);

            $manager->persist($standardModList);
            $manager->flush();

            $this->addReference(self::ID, $standardModList);
        });
    }

    public function getDependencies(): array
    {
        return [
            User2Fixture::class, // owner

            R3ModFixture::class,
            ArmaScriptProfilerModFixture::class,

            AceInteractionMenuExpansionModFixture::class,
            ArmaForcesAceMedicalModFixture::class,
            LegacyArmaForcesModsModFixture::class,
            ArmaForcesJbadBuildingFixModFixture::class,
            ArmaForcesMedicalModFixture::class,

            RhsModGroupFixture::class,

            CslaIronCurtainDlcFixture::class,
        ];
    }
}

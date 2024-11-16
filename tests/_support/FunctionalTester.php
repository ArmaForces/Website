<?php

declare(strict_types=1);

namespace App\Tests;

use App\Shared\Test\Traits\TimeTrait;
use App\Tests\Traits\CommonAssertsTrait;
use App\Tests\Traits\CommonPageAssertsTrait;
use App\Tests\Traits\DataTableAssertsTrait;
use App\Tests\Traits\ResponseAssertTrait;
use App\Tests\Traits\SecurityAssertsTrait;
use Codeception\Actor;
use Codeception\Lib\Friend;

/**
 * Inherited Methods.
 * @method void   wantToTest($text)
 * @method void   wantTo($text)
 * @method void   execute($callable)
 * @method void   expectTo($prediction)
 * @method void   expect($prediction)
 * @method void   amGoingTo($argumentation)
 * @method void   am($role)
 * @method void   lookForwardTo($achieveValue)
 * @method void   comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends Actor
{
    use _generated\FunctionalTesterActions;
    use CommonAssertsTrait;
    use CommonPageAssertsTrait;
    use DataTableAssertsTrait;
    use ResponseAssertTrait;
    use SecurityAssertsTrait;
    use TimeTrait;
}

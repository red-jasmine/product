<?php

namespace RedJasmine\Product\Tests\Application;

use RedJasmine\Product\Tests\Fixtures\Users\User;
use RedJasmine\Product\Tests\TestCase;
use RedJasmine\Support\Contracts\UserInterface;

class ApplicationTestCase extends TestCase
{

    public function user() : UserInterface
    {
        return User::make(1);
    }

}

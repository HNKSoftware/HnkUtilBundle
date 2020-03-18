<?php

namespace HNK\HnkUtilBundle\Tests;

use PHPUnit\Framework\TestCase;
use Hnk\HnkUtilBundle\Helper\DummyHelper;

class DummyHelperTest extends TestCase
{

    public function testReturnTrue(): void
    {
        $this->assertEquals(true, DummyHelper::returnTrue());
    }
}
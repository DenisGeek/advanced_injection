<?php

namespace Tests;

use AdvancedInjection\DataClass;
use AdvancedInjection\ExecClassWithInjection;
use PHPUnit\Framework\TestCase;
use AdvancedInjection\ExecClassWithoutInjection;

class ExecClassTest extends TestCase
{
    public function testExecWithInstanceWithoutExceptionInExecClass()
    {
        $data = new DataClass();
        $executor = new ExecClassWithInjection($data);

        $this->assertEquals('secret', $executor->exec());
    }

    public function testExecWithInheritInstanceWithExceptionInExecClass()
    {
        $data = new class extends DataClass {
            public function getData(): string
            {
                return '';
            }
        };

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Mocked');

        $executor = new ExecClassWithInjection($data);
        $executor->exec();
    }

    public function testExecWithInheritInstanceWithExceptionInDataClass()
    {
        $data = new class extends DataClass {
            public function __construct()
            {
            }
        };

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Storage empty');

        $executor = new ExecClassWithInjection($data);
        $executor->exec();
    }

    public function testConstructorWithInheritInstanceWithoutExceptionInExecClass()
    {
        $data = new class extends DataClass {
            public function load():array
            {
                return [1];
            }
        };

        $executor = new ExecClassWithInjection($data);
        $this->assertInstanceOf(ExecClassWithInjection::class, $executor);
    }

    public function testConstructorWithInheritInstanceWithExceptionInExecClass()
    {
        $data = new class extends DataClass {
            public function load():array
            {
                return [];
            }
        };

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unstatused');

        new ExecClassWithInjection($data);
    }
}

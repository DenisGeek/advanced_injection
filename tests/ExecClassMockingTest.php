<?php

namespace Tests;

use AdvancedInjection\DataClass;
use AdvancedInjection\ExecClassWithInjection;
use PHPUnit\Framework\TestCase;
use AdvancedInjection\ExecClassWithoutInjection;

class ExecClassTest extends TestCase
{
    public function testExecWithMockWithoutExceptionInExecClass()
    {
        //$data = $this->getMockBuilder(DataClass::class)->setMethods(['getData'])->getMock();
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('getData')->willReturn('injected');
        $data->expects($this->once())->method('load')->willReturn([1]);

        $executor = new ExecClassWithInjection($data);
        $result = $executor->exec();

        $this->assertEquals('injected', $result);
    }

    public function testExecWithMockWithExceptionInExecClass()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('getData')->willReturn('');
        $data->expects($this->once())->method('load')->willReturn([1]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Mocked');

        $executor = new ExecClassWithInjection($data);
        $executor->exec();
    }

    public function testConstructorMockWithoutExceptionInExecClass()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('load')->willReturn([1]);

        $executor = new ExecClassWithInjection($data);
        $this->assertInstanceOf(ExecClassWithInjection::class, $executor);
    }

    public function testConstructorMockWithExceptionInExecClass()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('load')->willReturn([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unstatused');

        new ExecClassWithInjection($data);
    }

    public function testExecMockWithExceptionInDataClass()
    {
        $data = $this->getMockBuilder(DataClass::class)
                    ->getMock();
        $data->expects($this->once())->method('load')->willReturn([1]);
        $data->expects($this->once())->method('getData')->willThrowException(new \Exception('Storage empty'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Storage empty');

        $executor = new ExecClassWithInjection($data);
        $executor->exec();
    }
}

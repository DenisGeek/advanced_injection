<?php

namespace Tests;

use AdvancedInjection\DataClass;
use AdvancedInjection\ExecClassWithInjection;
use PHPUnit\Framework\TestCase;
use AdvancedInjection\ExecClassWithoutInjection;

/**
 * Prevent setting the class alias for all test suites
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExecClassTest extends TestCase
{
    public function testExecWithInstanceWithoutException()
    {
        $data = new DataClass();
        $executor = new ExecClassWithInjection($data);

        $this->assertEquals('secret', $executor->exec());
    }

    public function testExecWithInheritInstanceWithException()
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

    public function testConstructorWithInheritInstanceWithoutException()
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

    public function testConstructorWithInheritInstanceWithException()
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

    public function testExecWithMockWithoutException()
    {
        //$data = $this->getMockBuilder(DataClass::class)->setMethods(['getData'])->getMock();
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('getData')->willReturn('injected');
        $data->expects($this->once())->method('load')->willReturn([1]);

        $executor = new ExecClassWithInjection($data);
        $result = $executor->exec();

        $this->assertEquals('injected', $result);
    }

    public function testExecWithMockWithException()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('getData')->willReturn('');
        $data->expects($this->once())->method('load')->willReturn([1]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Mocked');

        $executor = new ExecClassWithInjection($data);
        $executor->exec();
    }

    public function testConstructorMockWithoutException()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('load')->willReturn([1]);

        $executor = new ExecClassWithInjection($data);
        $this->assertInstanceOf(ExecClassWithInjection::class, $executor);
    }

    public function testConstructorMockWithException()
    {
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('load')->willReturn([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unstatused');

        new ExecClassWithInjection($data);
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testExecWithMockeryWithoutException()
    {
        $dataExternal = \Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('getData')
            ->once()
            ->andReturn('forwarded');
        $dataExternal->shouldReceive('load')
            ->once()
            ->andReturn([1]);

        $executor = new ExecClassWithoutInjection();

        $this->assertEquals('forwarded', $executor->exec());
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testExecWithMockeryWithException()
    {
        $dataExternal = \Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('getData')
            ->once()
            ->andReturn('');
        $dataExternal->shouldReceive('load')
            ->once()
            ->andReturn([1]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Mocked');

        $executor = new ExecClassWithoutInjection();
        $executor->exec();
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testConstructorWithMockeryWithoutException()
    {
        $dataExternal = \Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('getData')
                    ->once()
                    ->andReturn('forwarded');
        $dataExternal->shouldReceive('load')
                    ->once()
                    ->andReturn([1]);

        $executor = new ExecClassWithoutInjection();
        $this->assertEquals('forwarded', $executor->exec());
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testConstructorWithMockeryWithException()
    {
        $dataExternal = \Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('getData')
                    ->once()
                    ->andReturn('forwarded');
        $dataExternal->shouldReceive('load')
                    ->once()
                    ->andReturn([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unstatused');

        $executor = new ExecClassWithoutInjection();
    }
}

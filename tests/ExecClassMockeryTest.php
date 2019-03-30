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
    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testExecWithMockeryWithoutExceptionInExecClass()
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
    public function testExecWithMockeryWithExceptionInExecClass()
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
    public function testConstructorWithMockeryWithoutExceptionInExecClass()
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
    public function testConstructorWithMockeryWithExceptionInExecClass()
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

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testConstructorWithMockeryWithExceptionInDataClass()
    {
        $dataExternal = \Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('load')
            ->once()
            ->andReturn([1]);
        $dataExternal->shouldReceive('getData')
            ->once()
            ->andThrow(new \Exception('Storage empty'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Storage empty');

        $executor = new ExecClassWithoutInjection();
        $executor->exec();
    }
}

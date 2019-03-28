<?php

use Test\DataClass;
use Test\ExecClassWithInjection;
use PHPUnit\Framework\TestCase;
use Test\ExecClassWithoutInjection;

/**
 * Prevent setting the class alias for all test suites
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExecClassTest extends TestCase
{
    public function testExecClassWithInstance()
    {
        $data = new DataClass();
        $executor = new ExecClassWithInjection($data);

        $this->assertEquals('secret', $executor->exec());
    }

    public function testExecClassWithMock()
    {
        //$data = $this->getMockBuilder(DataClass::class)->setMethods(['getData'])->getMock();
        $data = $this->createMock(DataClass::class);
        $data->expects($this->once())->method('getData')->willReturn('injected');

        $executor = new ExecClassWithInjection($data);
        $result = $executor->exec();

        $this->assertEquals('injected', $result);
    }

    /**
     * @runInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testExecClassWithMockeryMock()
    {
        $dataExternal = Mockery::mock('overload:' . DataClass::class);
        $dataExternal->shouldReceive('getData')
                    ->once()
                    ->andReturn('forwarded');

        $executor = new ExecClassWithoutInjection();
        $this->assertEquals('forwarded', $executor->exec());
    }
}

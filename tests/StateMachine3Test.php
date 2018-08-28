<?php

declare(strict_types=1);

namespace Tests\App;

use App\Entity\TrafficLight;
use App\StateMachine;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;

class StateMachine3Test extends TestCase
{
    /** @var  StateMachine */
    private $sm;
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->sm = new StateMachine( [
            'green' => [
                'to_yellow' =>  'yellow',
            ],
            'yellow' => [
                'to_green' => 'green',
                'to_red' => 'red',
            ],
            'red' => [
                'to_yellow' =>  'yellow',
            ],
        ]);
    }


    /**
     * @dataProvider canProvider
     */
    public function testCan(string $currentState, string $transition, bool $result)
    {
        $trafficLight = new TrafficLight($currentState);
        if ($result) {
            $this->assertTrue($this->sm->can($trafficLight, $transition), sprintf('We should be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        } else {
            $this->assertFalse($this->sm->can($trafficLight, $transition), sprintf('We should NOT be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        }
    }

    /**
     * @dataProvider applyProvider
     */
    public function testApply(string $currentState, string $newState, string $exception = null)
    {
        $trafficLight = new TrafficLight($currentState);
        if (null !== $exception) {
            $this->expectException($exception);
        }

        $this->sm->apply($trafficLight, sprintf('to_%s', $newState));
        $this->assertEquals($newState, $trafficLight->getState(), sprintf('A state machine with state "%s" should have updated to "%s" after ->apply("%s")', $currentState, $newState, $newState));
    }
}

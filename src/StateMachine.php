<?php


namespace App;


use App\Entity\StateAwareInterface;
use App\Entity\TrafficLight;

class StateMachine
{
    /**
     * @var array
     * 'green' => [
     * 'to_yellow' => 'yellow',
     * ],
     * 'yellow' => [
     * 'to_green' => 'green',
     * 'to_red' => 'red',
     * ],
     * 'red' => [
     * 'to_yellow' => 'yellow',
     * ],
     */
    private $statesWorkflow = [];

    /**
     * StateMachine constructor.
     * @param array $statesWorkflow
     */
    public function __construct(array $statesWorkflow)
    {
        $this->statesWorkflow = $statesWorkflow;
    }

    /**
     * Check if we are allowed to apply $state right now. Ie, is there an transition
     * from $this->state to $state?
     */
    public function can(StateAwareInterface $trafficLight, string $transition): bool
    {
        return array_key_exists($transition, $this->statesWorkflow[$trafficLight->getState()]);
    }

    /**
     * This will update $this->state.
     *
     * @throws \InvalidArgumentException if the $newState is invalid.
     */
    public function apply(StateAwareInterface $trafficLight, string $transition): void
    {
        if (!$this->can($trafficLight, $transition)) {
            throw new \InvalidArgumentException();
        }

        $trafficLight->setState($this->statesWorkflow[$trafficLight->getState()][$transition]);
    }

    public function getCurrentState(StateAwareInterface $trafficLight): string
    {
        return $trafficLight->getState();
    }

    public function getValidTransitions(StateAwareInterface $trafficLight): array
    {
        return array_keys($this->statesWorkflow[$trafficLight->getState()]);
    }
}

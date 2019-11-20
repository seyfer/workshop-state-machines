<?php

declare(strict_types=1);

namespace App;

class TrafficLightStateMachine
{
    /** @var string State. (Exercise 1) This variable name is used in tests. Do not rename. */
    private $state;

    private $transitions = [
        'green' => ['to_yellow'],
        'yellow' => ['to_red', 'to_green'],
        'red' => ['to_yellow'],
    ];

    private $workflow = [
        'to_green' => 'green',
        'to_red' => 'red',
        'to_yellow' => 'yellow',
    ];

    /**
     * Check if we are allowed to apply $state right now. Ie, is there an transition
     * from $this->state to $state?
     */
    public function can(string $transition): bool
    {
        return in_array($transition, $this->transitions[$this->state], true);
    }

    /**
     * This will update $this->state.
     *
     * @throws \InvalidArgumentException if the $newState is invalid.
     */
    public function apply(string $transition): void
    {
        if (!$this->can($transition)) {
            throw new \InvalidArgumentException();
        }

        $this->state = $this->workflow[$transition];
    }
}

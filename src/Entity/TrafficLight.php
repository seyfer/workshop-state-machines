<?php


namespace App\Entity;


class TrafficLight implements StateAwareInterface
{
    /**
     * @var string
     */
    private $state;

    public function __construct(string $state)
    {
        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}

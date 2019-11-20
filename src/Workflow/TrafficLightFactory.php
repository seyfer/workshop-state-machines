<?php


namespace App\Workflow;


use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Transition;

class TrafficLightFactory
{
    public function create(): StateMachine
    {
        $definitionBuilder = new DefinitionBuilder();
        $definition = $definitionBuilder->addPlaces(['green', 'yellow', 'red'])
            // Transitions are defined with a unique name, an origin place and a destination place
            ->addTransition(new Transition('to_yellow', ['green', 'red'], 'yellow'))
            ->addTransition(new Transition('to_red', 'yellow', 'red'))
            ->addTransition(new Transition('to_green', 'yellow', 'green'))
            ->setInitialPlaces('green')
            ->build();

        $singleState = true; // true if the subject can be in only one state at a given time
        $property = 'state'; // subject property name where the state is stored
        $marking = new MethodMarkingStore($singleState, $property);

        return new StateMachine($definition, $marking, new EventDispatcher());
    }
}

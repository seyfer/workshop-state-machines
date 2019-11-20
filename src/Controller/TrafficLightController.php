<?php

namespace App\Controller;

use App\Entity\TrafficLight;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;

/**
 * @Route("/traffic-light")
 */
class TrafficLightController extends Controller
{
//    /**
//     * @var Registry
//     */
//    private $registry;
//
//    /**
//     * TrafficLightFactory constructor.
//     * @param $registry
//     */
//    public function __construct(Registry $registry, StateMachine $stateMachine)
//    {
//        $this->registry = $registry;
//        $this->registry->addWorkflow($stateMachine, new InstanceOfSupportStrategy(TrafficLight::class));
//    }

    /**
     * @Route("/", name="traffic_light_index")
     */
    public function indexAction()
    {
        return $this->render('traffic_light/index.html.twig', [
            'traffic_lights' => $this->get('doctrine')->getRepository(TrafficLight::class)->findAll(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="traffic_light_create")
     */
    public function createAction(Request $request)
    {
        $task = new TrafficLight($request->request->get('street', 'First street'));

        $em = $this->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();

        return $this->redirect($this->generateUrl('traffic_light_show', ['id' => $task->getId()]));
    }

    /**
     * @Route("/show/{id}", name="traffic_light_show")
     */
    public function showAction(TrafficLight $trafficLight)
    {
        return $this->render('traffic_light/show.html.twig', [
            'traffic_light' => $trafficLight,
        ]);
    }

    /**
     * @Route("/apply-transition/{id}", methods={"POST"}, name="traffic_light_apply_transition")
     */
    public function applyTransitionAction(Request $request, TrafficLight $trafficLight)
    {
        try {
            $this->get('state_machine.traffic_light')
                ->apply($trafficLight, $request->request->get('transition'));

            $this->get('doctrine')->getManager()->flush();
        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('traffic_light_show', ['id' => $trafficLight->getId()])
        );
    }

    /**
     * @Route("/reset-marking/{id}", methods={"POST"}, name="traffic_light_reset_marking")
     */
    public function resetMarkingAction(TrafficLight $task)
    {
        $task->setState('green');
        $this->get('doctrine')->getManager()->flush();

        return $this->redirect($this->generateUrl('traffic_light_show', ['id' => $task->getId()]));
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\EmailAddressType;
use App\Form\FavoriteColorType;
use App\Form\NameType;
use App\Form\TwitterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\StateMachine;

class SignupController extends Controller
{
    private $em;

    /**
     * @var StateMachine
     */
    private $stateMachine;

    /**
     *
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, StateMachine $stateMachine)
    {
        $this->em = $em;
        $this->stateMachine = $stateMachine;
    }

    private function applyTransition(UserProfile $userProfile, string $transition): string
    {
        $newPlaces = $this->stateMachine->apply($userProfile, $transition)->getPlaces();
        $newPlace = array_keys($newPlaces)[0];
        $route = $this->stateMachine->getMetadataStore()->getPlaceMetadata($newPlace)['route'];

        return $route;
    }

    private function applyAndPersist(UserProfile $userProfile, string $transition): string
    {
        $route = $this->applyTransition($userProfile, $transition);

        $this->em->persist($userProfile);
        $this->em->flush();

        return $route;
    }

    /**
     * @Route("/signup/start", name="signup_start")
     */
    public function start(Request $request)
    {
        $user = new UserProfile();

        $form = $this->createForm(NameType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $route = $this->applyAndPersist($user, 'to_email');

            return $this->redirectToRoute($route, ['id' => $user->getId()]);
        }

        return $this->render('signup/start.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/email/{id}", name="signup_email")
     */
    public function email(Request $request, UserProfile $user)
    {
        $form = $this->createForm(EmailAddressType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $route = $this->applyAndPersist($user, 'to_twitter');

            return $this->redirectToRoute($route, ['id' => $user->getId()]);
        }

        return $this->render('signup/email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/twitter/{id}", name="signup_twitter")
     */
    public function twitter(Request $request, UserProfile $user)
    {
        $form = $this->createForm(TwitterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $route = $this->applyAndPersist($user, 'to_color');

            return $this->redirectToRoute($route, ['id' => $user->getId()]);
        }

        return $this->render('signup/twitter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/color/{id}", name="signup_color")
     */
    public function color(Request $request, UserProfile $user)
    {
        $form = $this->createForm(FavoriteColorType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $route = $this->applyAndPersist($user, 'to_done');

            return $this->redirectToRoute($route, ['id' => $user->getId()]);
        }

        return $this->render('signup/color.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/done/{id}", name="signup_done")
     */
    public function done(UserProfile $user)
    {
        return $this->render('signup/done.html.twig', [
            'user' => $user,
        ]);
    }
}

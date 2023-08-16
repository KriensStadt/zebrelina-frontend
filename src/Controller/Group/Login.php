<?php

declare(strict_types=1);

namespace App\Controller\Group;

use App\Entity\DeviceGroup;
use App\Entity\TimePeriod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/group/login/{group}/{timePeriod}', name: 'group.login', defaults: ['group' => null, 'timePeriod' => null])]
class Login extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
    ) {
    }

    public function __invoke(Request $request, ?DeviceGroup $group, ?TimePeriod $timePeriod): Response
    {
        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('group/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'group' => $group,
            'timePeriod' => $timePeriod,
        ]);
    }
}

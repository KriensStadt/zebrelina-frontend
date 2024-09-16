<?php

declare(strict_types=1);

namespace App\Controller\Device;

use App\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/device/login/{device}/{password}', name: 'device.login', defaults: ['device' => null, 'password' => null])]
class Login extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
    ) {
    }

    public function __invoke(Request $request, ?Device $device, ?string $password): Response
    {
        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('device/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'device' => $device,
            'password' => $password,
        ]);
    }
}

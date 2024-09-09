<?php

declare(strict_types=1);

namespace App\Controller\Admin\Device;

use App\Entity\Device;
use App\Form\DeviceQrCodeWizardType;
use App\Service\LoginLinkQrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

#[Route(path: '/admin/device/qr-code-wizard', name: 'admin.device.qr_code_wizard')]
class QrCodeWizard extends AbstractController
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly LoginLinkQrCodeGenerator $loginLinkQrCodeGenerator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $qrCode = null;
        $form = $this->createForm(DeviceQrCodeWizardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ?string $password */
            $password = $form->get('password')->getData();

            /** @var Device $device */
            $device = $form->get('device')->getData();

            $url = $this->router->generate('device.login', parameters: ['device' => $device->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
            $qrCode = $this->loginLinkQrCodeGenerator->generate($url, $password, $device->getName());
        }

        return $this->render('admin/device/qr_code_wizard.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode,
        ]);
    }
}

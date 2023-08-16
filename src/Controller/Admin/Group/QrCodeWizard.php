<?php

declare(strict_types=1);

namespace App\Controller\Admin\Group;

use App\Entity\DeviceGroup;
use App\Entity\TimePeriod;
use App\Form\GroupQrCodeWizardType;
use App\Service\LoginLinkQrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

#[Route(path: '/admin/group/qr-code-wizard', name: 'admin.group.qr_code_wizard')]
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
        $form = $this->createForm(GroupQrCodeWizardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ?string $password */
            $password = $form->get('password')->getData();

            /** @var DeviceGroup $group */
            $group = $form->get('group')->getData();

            /** @var TimePeriod $timePeriod */
            $timePeriod = $form->get('timePeriod')->getData();

            $url = $this->router->generate('group.login', parameters: ['group' => $group->getId(), 'timePeriod' => $timePeriod->getId()], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
            $qrCode = $this->loginLinkQrCodeGenerator->generate($url, $password);
        }

        return $this->render('admin/group/qr_code_wizard.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode,
        ]);
    }
}

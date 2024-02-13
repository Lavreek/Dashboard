<?php

namespace App\Controller;

use App\Entity\Calls;
use App\Entity\Mails;
use App\Form\Calls\CreateType as CallsCreate;
use App\Form\Mails\CreateType as MailsCreate;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComplexController extends AbstractController
{
    #[Route('/complex', name: 'app_complex')]
    public function index(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $mailsForm = $this->formProcessed(MailsCreate::class, Mails::class, $request, $managerRegistry);
        $callsForm = $this->formProcessed(CallsCreate::class, Calls::class, $request, $managerRegistry);

        return $this->render('complex/index.html.twig', [
            'mails_form' => $mailsForm,
            'calls_form' => $callsForm,
        ]);
    }

    private function formProcessed($form, $class, $request, $manager)
    {
        $form =  $this->createForm($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }
    }
}

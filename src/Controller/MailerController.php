<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, EntityManagerInterface $em, Request $request): Response
    {
        $mail = new Mail();
        $form = $this->createForm(ContactType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail->setRecipient('wf3camp@gmail.com');
            $email = (new Email())
            ->from($mail->getSender())
            ->to('wf3camp@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($mail->getSubject())
            ->text('Sending emails is fun again!')
            ->html($mail->getContent());

            $mailer->send($email);

            $em->persist($mail); //? On conserve une trace du mail
            $em->flush(); //? Execute toutes les opérations mises en cache (transactions SQL)

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('mailer/index.html.twig', [
            'form' => $form,
            'action' => 'Envoyer le mail',
        ]);
    }
}
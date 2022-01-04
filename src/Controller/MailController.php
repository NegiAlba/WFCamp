<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, Request $request, EntityManagerInterface $em): Response
    {
        $mail = new Mail();
        $formulaire = $this->createForm(ContactType::class, $mail);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            //Ici on va persister mon mail dans la base de données
            $mail->setRecipient('wf3camp@gmail.com');

            $em->persist($mail);
            $em->flush();

            //Ici on va réaliser l'envoi de mail
            $email = (new TemplatedEmail())
            ->from($mail->getSender())
            ->to($mail->getRecipient())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($mail->getSubject())
            // ->text('Sending emails is fun again!')
            ->html('Salut les amis\n!')
            ->htmlTemplate('mail/contact.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => $mail->getSender(),
                'content' => $mail->getContent(),
            ]);

            $mailer->send($email);
        }

        return $this->renderForm('mail/index.html.twig', [
            'form' => $formulaire,
            'action' => 'Envoyer le mail',
        ]);
    }
}
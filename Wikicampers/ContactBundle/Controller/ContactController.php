<?php

namespace Aurel\ContactBundle\Controller;

//        return $this->render('@WikicampersContact/Default/index.html.twig');

use Aurel\ContactBundle\Entity\ContactBundle;
use Aurel\ContactBundle\Form\ContactBundleType;
use Aurel\ContactBundle\Repository\ContactBundleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{

//    /**
//     * @var ContactRepository
//     */
//    private $repositiory;
//
//    public function __construct(ContactRepository $repository)
//    {
//        $this->repositiory = $repository;
//
//    }

    /**
     * @return Response
     */
    public function homeAction(ContactBundleRepository $repository)
    {
//        $data = $this->repositiory->findAll();
        $data = $repository->findAll();
        return $this->render('@WikicampersContact/Default/accueil.html.twig',['controller_name' => 'ContactController', 'contacts' => $data]);

    }

    public function formAction(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer)
    {
        $contact = new ContactBundle();

        $form = $this->createForm(ContactBundleType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTime());
            $manager->persist($contact);
            $manager->flush();

            /**
             * Mail
             */
            $email = (new \Swift_Message('Donneé infos'))
                ->setFrom($form->get('mail')->getData())
                ->setTo('patxi.laduche@wikicampers.fr')
                ->setBody(
                    $this->renderView(
                        'form/email.html.twig',
                        [
                            'prenom' => $form->get('prenom')->getData(),
                            'nom' => $form->get('nom')->getData(),
                            'mail'=> $form->get('mail')->getData(),
                            'description' => $form->get('description')->getData()
                        ]),'text/html'
                );
            $mailer->send($email);

            /**
             * Mail de confirmation
             */
            $messageConfirmation = (new \Swift_Message('Donneé infos'))
                ->setFrom($form->get('mail')->getData())
                ->setTo($form->get('mail')->getData())
                ->setBody(
                    $this->renderView(
                        'form/confirmation.html.twig',
                        [
                            'prenom' => $form->get('prenom')->getData(),
                            'nom' => $form->get('nom')->getData(),
                            'mail'=> $form->get('mail')->getData(),
                            'description' => $form->get('description')->getData()
                        ]),'text/html'
                );
            $mailer->send($messageConfirmation);

            $this->addFlash('success', 'Votre mail à bien été envoyé');

//            return $this->redirectToRoute('accueil');
        }
        return $this->render('@WikicampersContact/Default/formulaire.html.twig', ['form' => $form->createView()]);
    }

}

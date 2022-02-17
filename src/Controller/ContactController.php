<?php

namespace App\Controller;


use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');

            //dd($form->get('nom'));

            $mail = new Mail();
           // $content ="Bonjour"." ".$order->getUser()->getFirstname()."<br/>"."Merci pour votre commande"."<br/><br/>"."Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,molestiae quas vel sint commodi repudiandae consequuntur voluptatum labor";
            $mail->send('bibrahimalella@gmail.com','ibrahima', 'Mail de Contact', "Bonjour Ibrahim <br/> Nous avon bien reçu votre email");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

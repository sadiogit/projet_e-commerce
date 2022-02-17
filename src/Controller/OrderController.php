<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {   

        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        

        // pour recupérer les infos de la commande
        $cart = $cart->getFull();

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }

     /**
     * @Route("/commande/racapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();

            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .='<br/>'.$delivery->getPhone();

            if($delivery->getCompany()){
                $delivery_content .='<br/>'.$delivery->getCompany();
            }

            $delivery_content .='<br/>'.$delivery->getAddress();
            $delivery_content .='<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .='<br/>'.$delivery->getCountry();
            

            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            // $order->setIsPaid(0);
            $order->setState(0);

            $this->entityManager->persist($order);

         /*   $product_for_stripe = [];
            $YOUR_DOMAIN = 'http://127.0.0.1:8000';
            */


            foreach($cart->getFull() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);

            /*
                $product_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product['product']->getPrice(),
                        'product_data' => [
                          'name' => $product['product']->getName(),
                          'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                        ],
                      ],

                      'quantity' => $product['quantity'],
                    ];

                    */
            }


            //dd($product_for_stripe);

             $this->entityManager->flush();


                //dump($checkout_session->id);
                //dd($checkout_session);
            // pour recupérer les infos de la commande
        $cart = $cart->getFull();

        return $this->render('order/add.html.twig', [
            'cart' => $cart,
            'carrier' => $carriers,
            'delivery' => $delivery_content,
            'reference' => $order->getReference(),
           // 'stripe_checkout_session' => $checkout_session->id
        ]);

        }

        return $this->redirectToRoute('cart');
        
    }
}

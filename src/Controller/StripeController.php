<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        
        if(!$order){
             new JsonResponse(['error'=> 'order']);
        }

       // dd($order->getOrderDetails()->getValues());

        //foreach($cart->getFull() as $product){
        foreach($order->getOrderDetails()->getValues() as $product){
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    //'unit_amount' => $product['product']->getPrice(),
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                      //'name' => $product['product']->getName(),
                      'name' => $product->getProduct(),
                      //'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                      'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                  ],

                  'quantity' => $product->getQuantity(),
                ];

               
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                //'unit_amount' => $product['product']->getPrice(),
                //'unit_amount' => $order->getCarrierPrice() * 100,
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                  //'name' => $product['product']->getName(),
                  'name' => $order->getCarrierName(),
                  //'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                  'images' => [$YOUR_DOMAIN],
                ],
              ],

              'quantity' => 1,
            ];


       //dd($product_for_stripe);
                           
        Stripe::setApiKey('sk_test_51IQb34AcMky6wC6XB7B05jqKRL7k2vTrz3tBTgGDGaOG3XfPHG5aL4bP3INwyjqUcCPhWMndwnqZJNyP89v0nshX00hGZX6Jo6');
               
               
        $checkout_session = Session::create([
          'customer_email' => $this->getUser()->getEmail(),
          'payment_method_types' => ['card'],
          'line_items' => [
                $product_for_stripe

              ],
          'mode' => 'payment',
          'success_url' => $YOUR_DOMAIN.'/commande/merci/{CHECKOUT_SESSION_ID}',
          //'success_url' => $YOUR_DOMAIN.'/success.html',
        //'cancel_url' => $YOUR_DOMAIN.'/error.html',
          'cancel_url' => $YOUR_DOMAIN.'/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

            $order->setStripeSessionId($checkout_session->id);
            $entityManager->flush();
                
        $response = new JsonResponse(['id'=> $checkout_session->id]);
        return $response;
    }
}

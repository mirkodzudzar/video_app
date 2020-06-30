<?php

namespace App\Controller;

use App\Entity\Subscription;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/pricing", name="pricing")
     */
    public function pricing() {

        return $this->render('front/pricing.html.twig', [
            'name' => Subscription::getPlanDataNames(),
            'price' => Subscription::getPlanDataPrices(),
        ]);
    }
}

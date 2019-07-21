<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

/**
 * @Route("/Product")
 */
class ProductController extends Controller
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/listProduct")
     * @Method({"POST", "GET"})
     */
    public function listProductAction(): JsonResponse
    {
        $this->logger->info("requested to list all products");
        $response = ['data' => [], 'success' => false, 'message' => ''];
        try {
            $em = $this->getDoctrine()->getManager();
            $products = $em->getRepository('AppBundle:Product')->findInfoBasic();
            $response = ['data' => $products, 'success' => true, 'message' => 'correctly loaded products'];
        } catch (\Exception $e) {
            $this->logger->error('presented an error in ProductController', ['message' => "" . $e->getMessage()]);
            $response['message'] = 'presented an error when loading products, request to load the page again';

        }
        return new JsonResponse($response);

    }

}

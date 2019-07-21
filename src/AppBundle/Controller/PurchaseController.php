<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Log\LoggerInterface;
use DateTime;


/**
 * @Route("/Purchase")
 */
class PurchaseController extends Controller
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/generatePurchase")
     * @Method({"POST", "GET"})
     */
    public function generatePurchaseAction(Request $request): JsonResponse
    {
        //TODO Falta validar los parametros que llegán por el request
        $this->logger->info("generate a purchase order");
        $response = ['data' => [], 'success' => false, 'message' => ''];
        try {
            $em = $this->getDoctrine()->getManager();
            if ($request->isMethod('POST')) {

                $dateTime = new DateTime();
                $name = $request->get("name");
                $email = $request->get("email");
                $mobile = $request->get("mobile");
                $address = $request->get("address");
                $status = 'CREATED';
                $reference = random_int(0, 1000) . '' . $dateTime->format('YmdHmi');
                $product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $request->get('idProduct')));
                $description = 'it bought a ' . $product->getName();
                $currency = $request->get("currency");
                $total = $product->getPrice();
                //TODO requestId y processUrl estan tomando valor de prueba temporal, el valor final de estos debe ser el enviado por el servicio de redirection de placetopay
                $requestId = 288393;
                $processUrl = 'https://dev.placetopay.com/redirection/session/' . $requestId . '/' . $reference;

                $purchase = new Purchase($name, $email,
                    $mobile, $address, $status,
                    $reference, $description, $currency,
                    $total, $processUrl, $requestId, $product);
                $em->persist($purchase);
                $em->flush();
            }
            $response = ['data' => [], 'success' => true, 'message' => 'order purchase successful generate'];
        } catch (\Exception $e) {
            $this->logger->error('presented an error in PurchaseController', ['message' => "" . $e->getMessage()]);
            $response['message'] = 'presented an error when generate purchase, request to load the page again';
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/listPurchase")
     * @Method({"POST", "GET"})
     */
    public function listPurchaseAction(): JsonResponse
    {
        $this->logger->info("list all purchase orders");
        $response = ['data' => [], 'success' => false, 'message' => ''];
        try {
            $em = $this->getDoctrine()->getManager();
            $purchase = $em->getRepository('AppBundle:Purchase')->findPurchase();
            $response = ['data' => $purchase, 'success' => true, 'message' => 'correctly loaded orders purchase'];
        } catch (\Exception $e) {
            $this->logger->error('presented an error in PurchaseController', ['message' => "" . $e->getMessage()]);
            $response['message'] = 'presented an error when loading purchase, request to load the page again';

        }
        return new JsonResponse($response);
    }


}

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

                $newPayment = $this->buildStructure($request, $reference, $description, $total);
                $connectionPtoP = PlacetopayController::initConnection($this->container->getParameter('IDENTIFICATOR'),
                    $this->container->getParameter('SECRETKEY'), $this->container->getParameter('ENDPOINT'));
                $result = $connectionPtoP->request($newPayment);;
                if ($result->isSuccessful()) {
                    $requestId = $result->requestId();
                    $processUrl = $result->processUrl();

                    $purchase = new Purchase($name, $email,
                        $mobile, $address, $status,
                        $reference, $description, $currency,
                        $total, $processUrl, $requestId, $product);
                    $em->persist($purchase);
                    $em->flush();

                    $response = ['data' => ['url' => $processUrl], 'success' => true, 'message' => 'order purchase successful generate'];
                } else {
                    $this->logger->error('presented an error in PurchaseController',
                        ['message' => "" . $result->status()->message()]);
                    $response['message'] = 'presented an error when generate purchase, request to load the page again';
                }
            }

        } catch (\Exception $e) {
            $this->logger->error('presented an error in PurchaseController', ['message' => "" . $e->getMessage()]);
            $response['message'] = 'presented an error when generate purchase, request to load the page again';
        }
        return new JsonResponse($response);
    }


    private function buildStructure(Request $request, string $reference, string $description, int $total): array
    {
        $data = [
            'payment' => [
                'reference' => $reference,
                'description' => $description,
                'amount' => [
                    'currency' => $request->get('currency'),
                    'total' => $total,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => $this->container->getParameter('RETURNURL'),//?reference=' . $reference,
            'ipAddress' => $request->server->get('REMOTE_ADDR'),
            'userAgent' => $request->headers->get('user-agent'),
        ];

        return $data;
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

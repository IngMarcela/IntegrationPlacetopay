<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\Product;

class LoadPurchaseData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $productList = ['tv', 'comedor', 'dvd', 'licuadora', 'placha'];
        $numberProduct = random_int(0, 4);
        $product = new Product($productList[$numberProduct], random_int(10000, 50000));
        $manager->persist($product);
        $manager->flush();

        $nameList = ['Marcela', 'Jose', 'Lola', 'Luciana'];
        $emailList = ['marcela@dominio.com', 'jose@dominio.com', 'lola@dominio.com', 'luciana@dominio.com'];
        $mobileList = ['7938647295', '5683926382', '8272362384', '7232396533'];
        $addressList = ['calle', 'sucursal', 'carrera', 'manzana'];
        $statusList = ['CREATED', 'PAYED', 'REJECTED'];
        $referenceList = ['568853257', '736879634', '367890456', '345678847'];
        $randomReference = $referenceList[random_int(0, 3)];
        $descriptionList = [
            'compra de Tv',
            'compra de comedor',
            'compra de dvd',
            'compra de licuadora',
            'compra de plancha'
        ];
        $currencyList = ['COP', 'USD'];
        $totalList = [123456, 23600, 54978, 934034];
        $requestIdList = [288393, 834834, 8348374, 8347384];
        $randomRequestId = $requestIdList[random_int(0, 3)];
        $proceesUrl = 'https://dev.placetopay.com/redirection/session/'.$randomRequestId.'/'.$randomReference;
        $purchase = new Purchase($nameList[random_int(0, 3)], $emailList[random_int(0, 3)],
            $mobileList[random_int(0, 3)], $addressList[random_int(0, 3)], $statusList[random_int(0, 2)],
            $randomReference, $descriptionList[$numberProduct], $currencyList[random_int(0, 1)],
            $totalList[random_int(0, 3)], $proceesUrl, $randomRequestId, $product);
        $manager->persist($purchase);
        $manager->flush();

        return $purchase;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
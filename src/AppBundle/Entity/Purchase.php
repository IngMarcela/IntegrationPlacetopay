<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="customerName", type="string", length=50)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="customerEmail", type="string", length=70)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="customerMobile", type="string", length=11)
     */
    private $customerMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="customerAddress", type="string", length=50)
     */
    private $customerAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=15)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var AppBundle\Entity\Purchase
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="purchase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProduct", referencedColumnName="id")
     * })
     */
    private $product;

    public function __construct(
        string $customerName,
        string $customerEmail,
        string $customerMobile,
        string $customerAddress,
        string $status,
        string $reference,
        string $description,
        string $currency,
        int $total,
        Product $product
    ) {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->customerName = $customerName;
        $this->customerMobile = $customerMobile;
        $this->customerAddress = $customerAddress;
        $this->customerEmail = $customerEmail;
        $this->status = $status;
        $this->reference = $reference;
        $this->description = $description;
        $this->currency = $currency;
        $this->total = $total;
        $this->product = $product;
    }

    /**
     * Get id by purchase
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get customer name by purchase
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
     * Get customer Email by purchase
     */
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * Get customer Mobile by purchase
     */
    public function getCustomerMobile(): string
    {
        return $this->customerMobile;
    }

    /**
     * Get customer Address by purchase
     */
    public function getCustomerAddress(): string
    {
        return $this->customerAddress;
    }

    /**
     * Set status of Purchase order
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get status by purchase
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get createdAt by purchase
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt by purchase
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt by purchase
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Get reference by purchase
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Get description by purchase
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get currency by purchase
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get total by purchase
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Get product by purchase
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}

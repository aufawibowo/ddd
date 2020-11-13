<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;


class Order
{
    const STATUS_ONORDER = 1;
    const STATUS_PREPARING = 2;
    const STATUS_SHIPPING = 3;
    const STATUS_RECEIVED = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_DONE = 6;

    private OrderId $orderId;
    private string $sellerId;
    private int $status;
    private Date $createdAt;
    private Date $updatedAt;
    private array $products;
    private array $invoice;
    private array $courier;
    private array $customer;
    private ?string $receiptNo;
    private string $shippingAddress;
    private int $amountTotal;
    private int $shippingTotal;
    private Date $expiredAt;

    public function __construct(
        OrderId $orderId,
        string $sellerId,
        int $status,
        Date $createdAt = null,
        Date $updatedAt = null,
        array $products = [],
        array $invoice = [],
        array $courier = [],
        array $customer = [],
        ?string $receiptNo = null,
        string $shippingAddress,
        int $amountTotal = 0,
        int $shippingTotal = 0,
        Date $expiredAt
    ) {
        $this->orderId = $orderId;
        $this->sellerId = $sellerId;
        $this->status = $status;
        $this->createdAt = $createdAt ?: new Date(new \DateTime());
        $this->updatedAt = $updatedAt ?: new Date(new \DateTime());
        $this->products = $products;
        $this->invoice = $invoice;
        $this->courier = $courier;
        $this->customer = $customer;
        $this->receiptNo = $receiptNo;
        $this->shippingAddress = $shippingAddress;
        $this->amountTotal = $amountTotal;
        $this->shippingTotal = $shippingTotal;
        $this->expiredAt = $expiredAt;
    }

    public function getId(): OrderId
    {
        return $this->orderId;
    }

    public function getSellerId(): string
    {
        return $this->sellerId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getReceiptNo(): ?string
    {
        return $this->receiptNo;
    }

    public function getShippingAdress(): ?string
    {
        return $this->shippingAddress;
    }

    public function getAmountTotal(): int
    {
        return $this->amountTotal;
    }

    public function getShippingTotal(): int
    {
        return $this->shippingTotal;
    }

    static function getStatusText(int $status): string
    {
        switch ($status) {
            case self::STATUS_ONORDER:
                return "On Order";

            case self::STATUS_PREPARING:
                return "Preparing";

            case self::STATUS_SHIPPING:
                return "Shipping";

            case self::STATUS_RECEIVED:
                return "Received";

            case self::STATUS_CANCELLED:
                return "Cancelled";

            case self::STATUS_DONE:
                return "Done";

            default:
                return "Unknown status";
        }
    }

    public function getExpiration(): ?Date
    {
        return $this->expiredAt;
    }

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function getInvoice(): array
    {
        return $this->invoice;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function getCourier(): array
    {
        return $this->courier;
    }

    public function getCreatedAt(): Date
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Date
    {
        return $this->updatedAt;
    }

    public function ownedBy($sellerId): bool
    {
        return $this->sellerId == $sellerId;
    }

    public function getOrderNextStatus(string $receiptNo = ""): int
    {
        if ($this->status == self::STATUS_ONORDER)
            return self::STATUS_PREPARING;
        elseif (
            $this->status == self::STATUS_PREPARING && $receiptNo != ""
        )
            return self::STATUS_SHIPPING;
        return $this->status;
    }
}

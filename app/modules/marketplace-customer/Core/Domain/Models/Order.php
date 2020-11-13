<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Order
{
    const STATUS_ONORDER = 1;
    const STATUS_PREPARING = 2;
    const STATUS_SHIPPING = 3;
    const STATUS_RECEIVED = 4;
    const STATUS_CANCELLED = 5;

    private OrderId $orderId;
    private string $invoiceId;
    private string $customerId;
    private ?string $receiptNo;
    private array $courierIds;
    private int $status;
    private ?array $cartIds;
    private ?string $productId;

    /**
     * Order constructor.
     * @param OrderId $orderId
     * @param string $invoiceId
     * @param string $customerId
     * @param array $courierIds
     * @param int $status
     */
    public function __construct(
        OrderId $orderId,
        string $invoiceId,
        string $customerId,
        string $receiptNo = null,
        array $courierIds,
        int $status,
        ?array $cartIds,
        ?string $productId
    ){
        $this->orderId = $orderId;
        $this->invoiceId = $invoiceId;
        $this->receiptNo = $receiptNo;
        $this->customerId = $customerId;
        $this->courierIds = $courierIds;
        $this->status = $status;
        $this->cartIds = $cartIds;
        $this->productId = $productId;
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

            default:
                return "Unknown status";
        }
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId->id();
    }

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getReceiptNo(): ?string
    {
        return $this->receiptNo;
    }

    /**
     * @return string
     */
    public function getCourierIds(): array
    {
        return $this->courierIds;
    }

    /**
     * @return array
     */
    public function getCart(): ?array
    {
        return $this->cartIds;
    }

    /**
     * @return void
     */
    public function setCart($cart): void
    {
        $this->cartIds = $cart;
    }

    /**
     * @return string
     */
    public function getProductId(): ?string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return (string)$this->status;
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

    public function validate(): array
    {
        $errors = [];

        if(!isset($this->orderId))
            $errors[] = 'order_id_must_be_specified';

        if(!isset($this->invoiceId))
            $errors[] = 'invoice_id_must_be_specified';

        if(!isset($this->customerId))
            $errors[] = 'customer_id_must_be_specified';

        if(!isset($this->status))
            $errors[] = 'status_must_be_specified';

        if ((count($this->cartIds) < 1) && (!isset($this->productId)))
            $errors[] = 'cart_id_or_product_id_must_be_specified';

        if (count($this->courierIds) < 1)
            $errors[] = 'courier_id_must_be_specified';

        return $errors;
    }
}
<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class ShippingProfile
{
    private ShippingProfileId $shippingProfileId;
    private ShippingAddress $shippingAddress;
    private string $namaPenerima;
    private string $nomorHpPenerima;
    private CustomerId $customerId;
    private float $latitude;
    private float $longitude;
    private string $label;
    private Date $created_at;
    private Date $updated_at;

    /**
     * ShippingProfile constructor.
     * @param ShippingProfileId $shippingProfileId
     * @param ShippingAddress $shippingAddress
     * @param string $namaPenerima
     * @param string $nomorHpPenerima
     * @param CustomerId $customerId
     * @param float $latitude
     * @param float $longitude
     * @param string $label
     * @param Date $created_at
     * @param Date $updated_at
     */
    public function __construct(ShippingProfileId $shippingProfileId, ShippingAddress $shippingAddress, string $namaPenerima, string $nomorHpPenerima, CustomerId $customerId, float $latitude, float $longitude, string $label, Date $created_at, Date $updated_at)
    {
        $this->shippingProfileId = $shippingProfileId;
        $this->shippingAddress = $shippingAddress;
        $this->namaPenerima = $namaPenerima;
        $this->nomorHpPenerima = $nomorHpPenerima;
        $this->customerId = $customerId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->label = $label;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getShippingProfileId(): string
    {
        return $this->shippingProfileId->id();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->shippingAddress->address();
    }

    /**
     * @return string
     */
    public function getNamaPenerima(): string
    {
        return $this->namaPenerima;
    }

    /**
     * @return string
     */
    public function getNomorHpPenerima(): string
    {
        return $this->nomorHpPenerima;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId->id();
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at->toDateTimeString();
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at->toDateTimeString();
    }
}
<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\AddShippingAddress;


class AddShippingAddressRequest
{
    public ?string $customerId;
    public ?string $address;
    public ?string $namaPenerima;
    public ?string $nomorHpPenerima;
    public ?string $latitude;
    public ?string $longitude;
    public ?string $label;

    /**
     * AddShippingAddressRequest constructor.
     * @param string|null $customerId
     * @param string|null $address
     * @param string|null $namaPenerima
     * @param string|null $nomorHpPenerima
     * @param string|null $latitude
     * @param string|null $longitude
     * @param string|null $label
     */
    public function __construct(?string $customerId, ?string $address, ?string $namaPenerima, ?string $nomorHpPenerima, ?string $latitude, ?string $longitude, ?string $label)
    {
        $this->customerId = $customerId;
        $this->address = $address;
        $this->namaPenerima = $namaPenerima;
        $this->nomorHpPenerima = $nomorHpPenerima;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->label = $label;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId)) {
            $errors[] = 'customerId_must_specified';
        }

        if (!isset($this->address)) {
            $errors[] = 'address_must_specified';
        }

        if (!isset($this->namaPenerima)) {
            $errors[] = 'namaPenerima_must_specified';
        }

        if (!isset($this->nomorHpPenerima)) {
            $errors[] = 'nomorHpPenerima_must_specified';
        }

        if (!isset($this->latitude)) {
            $errors[] = 'latitude_must_specified';
        }

        if (!isset($this->longitude)) {
            $errors[] = 'longitude_must_specified';
        }

        if (!isset($this->label)) {
            $errors[] = 'label_must_specified';
        }

        return $errors;
    }
}
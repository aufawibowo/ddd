<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;


class Storefront
{
    private StorefrontId $storefrontId;
    private string $sellerId;
    private string $name;

    public function __construct(
        StorefrontId $storefrontId,
        string $sellerId,
        string $name
    ) {
        $this->storefrontId = $storefrontId;
        $this->name = $name;
        $this->sellerId = $sellerId;
    }

    /**
     * @return StorefrontId
     */
    public function getId(): StorefrontId
    {
        return $this->storefrontId;
    }

    /**
     * @return string
     */
    public function getSellerId(): string
    {
        return $this->sellerId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function ownedBy($sellerId): bool
    {
        return $this->sellerId == $sellerId;
    }

    public function validate(): array
    {
        $errors = [];
        
        if (!isset($this->storefrontId)) {
            $errors[] = 'id_must_be_specified';
        }
        
        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->name)) {
            $errors[] = 'name_must_be_specified';
        }

        return $errors;
    }
}

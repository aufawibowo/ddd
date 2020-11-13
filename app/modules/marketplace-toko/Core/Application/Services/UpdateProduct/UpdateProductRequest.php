<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProduct;

class UpdateProductRequest
{
    public ?string $productId;
    public ?string $sellerId;
    public ?string $productName;
    public ?array $categories;
    public ?string $description;
    public ?int $stock;
    public ?int $price;
    public ?int $minOrder;
    public ?int $weight;
    public ?string $condition;
    public ?int $isActive;
    public ?string $storefrontId;
    public ?array $photos;
    public ?int $warranty;
    public ?int $warrantyPeriod;
    public ?string $brand;

    public function __construct(
        ?string $productId,
        ?string $sellerId,
        ?string $productName,
        ?array $categories,
        ?string $description,
        ?int $stock,
        ?int $price,
        ?int $minOrder,
        ?int $weight,
        ?string $condition,
        ?int $isActive,
        ?string $storefrontId,
        ?array $photos,
        ?int $warranty,
        ?int $warrantyPeriod,
        ?string $brand
    ) {
        $this->productId = $productId;
        $this->sellerId = $sellerId;
        $this->productName = $productName;
        $this->categories = $categories;
        $this->description = $description;
        $this->stock = $stock;
        $this->price = $price;
        $this->minOrder = $minOrder;
        $this->weight = $weight;
        $this->condition = $condition;
        $this->isActive = $isActive;
        $this->storefrontId = $storefrontId;
        $this->photos = $photos;
        $this->warranty = $warranty;
        $this->warrantyPeriod = $warrantyPeriod;
        $this->brand = $brand;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->productId))
            $errors[] = 'product_id_must_be_specified';

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->categories))
            $errors[] = 'category_must_be_specified';

        if (!isset($this->productName))
            $errors[] = 'product_name_must_be_specified';

        if (!isset($this->stock))
            $errors[] = 'stock_must_be_specified';

        if (!isset($this->price))
            $errors[] = 'price_must_be_specified';

        if (!isset($this->weight))
            $errors[] = 'weight_must_be_specified';

        if (!isset($this->condition))
            $errors[] = 'condition_must_be_specified';

        if (!isset($this->warranty))
            $errors[] = 'warranty_must_be_specified';

        if (!isset($this->warrantyPeriod))
            $errors[] = 'warranty_period_must_be_specified';

        if (!isset($this->brand))
            $errors[] = 'brand_must_be_specified';

        if (count($this->photos) > 10)
            $errors[] = 'the_max_num_of_photos_is_10';
        else
            foreach ($this->photos as $key => $value) {
                $flag = false;

                $extensionsAllowed = ["image/jpg", "image/jpeg", "image/png"];
                if (!in_array($value->getRealType(), $extensionsAllowed)) {
                    $errors[] = 'file_extension_not_allowed';
                    $flag = true;
                }
                if ($value->getSize() > 5000000) {
                    $errors[] = 'file_size_is_too_big';
                    $flag = true;
                }

                if ($flag) break;
            }

        return $errors;
    }
}

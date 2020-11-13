<?php


namespace A7Pro\Marketplace\Customer\Core\Application\Services\Product\SearchProduct;


class SearchProductRequest
{
    public ?string $keyword;
    public ?int $page;
    public ?int $limit;
    public ?string $sortKey;
    public ?string $order;
    public ?string $minimalPrice;
    public ?string $maximalPrice;
    public ?string $productLocation;

    /**
     * SearchProductRequest constructor.
     * @param string|null $keyword
     * @param int|null $page
     * @param int|null $limit
     * @param string|null $sortKey
     * @param string|null $order
     * @param string|null $minimalPrice
     * @param string|null $maximalPrice
     * @param string|null $productLocation
     */
    public function __construct(
        ?string $keyword,
        ?int $page,
        ?int $limit,
        ?string $sortKey,
        ?string $order,
        ?string $minimalPrice,
        ?string $maximalPrice,
        ?string $productLocation
    )
    {
        $this->keyword = $keyword;
        $this->page = $page;
        $this->limit = $limit;
        $this->sortKey = $sortKey;
        $this->order = $order;
        $this->minimalPrice = $minimalPrice;
        $this->maximalPrice = $maximalPrice;
        $this->productLocation = $productLocation;
    }


    public function validate():array
    {
        $errors = [];

        if (!isset($this->keyword)){
            $errors[] = 'keyword_must_be_specified';
        }

        return $errors;
    }
}
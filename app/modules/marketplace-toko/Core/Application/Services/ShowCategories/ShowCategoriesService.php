<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowCategories;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\CategoryRepository;

class ShowCategoriesService
{
    private CategoryRepository $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(ShowCategoriesRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->categoryRepository->getCategoriesList();
    }
}

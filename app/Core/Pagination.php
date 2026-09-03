<?php

declare(strict_types=1);

namespace App\Core;

class Pagination
{
    private int $totalItems;

    private int $currentPage;

    private int $perPage;

    private int $totalPages;

    public function __construct(
        int $totalItems,
        int $currentPage = 1,
        int $perPage = 15
    ) {
        $this->totalItems = max(
            0,
            $totalItems
        );

        $this->perPage = max(
            1,
            $perPage
        );

        $this->totalPages = max(
            1,
            (int) ceil(
                $this->totalItems /
                $this->perPage
            )
        );

        $this->currentPage = max(
            1,
            min(
                $currentPage,
                $this->totalPages
            )
        );
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getOffset(): int
    {
        return (
            $this->currentPage - 1
        ) * $this->perPage;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNextPage(): bool
    {
        return (
            $this->currentPage <
            $this->totalPages
        );
    }

    public function getPreviousPage(): ?int
    {
        if (!$this->hasPreviousPage()) {
            return null;
        }

        return $this->currentPage - 1;
    }

    public function getNextPage(): ?int
    {
        if (!$this->hasNextPage()) {
            return null;
        }

        return $this->currentPage + 1;
    }

    public function getPages(): array
    {
        return range(
            1,
            $this->totalPages
        );
    }
}
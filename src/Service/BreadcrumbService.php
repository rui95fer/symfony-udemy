<?php

namespace App\Service;

class BreadcrumbService
{
    private array $breadcrumbs = [];

    public function add(string $title, string $url = null): void
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url,
        ];
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function clear(): void
    {
        $this->breadcrumbs = [];
    }
}

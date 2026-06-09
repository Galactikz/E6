<?php

namespace App\Services;

use App\Models\Provider;
use App\Models\ProviderCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProviderService
{
    public function search(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Provider::with('category')
            ->active()
            ->orderByDesc('is_verified')
            ->orderByDesc('rating');

        if (!empty($filters['category'])) {
            $query->whereHas('category', fn(Builder $q) =>
                $q->where('slug', $filters['category'])
            );
        }

        if (!empty($filters['city'])) {
            $query->inCity($filters['city']);
        }

        if (!empty($filters['department'])) {
            $query->inDepartment($filters['department']);
        }

        if (!empty($filters['region'])) {
            $query->inRegion($filters['region']);
        }

        if (isset($filters['price_min']) || isset($filters['price_max'])) {
            $query->inBudget(
                $filters['price_min'] ?? null,
                $filters['price_max'] ?? null
            );
        }

        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('name', 'ILIKE', "%{$filters['search']}%")
                  ->orWhere('description', 'ILIKE', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findBySlug(string $categorySlug, string $city): Builder
    {
        return Provider::with('category')
            ->active()
            ->whereHas('category', fn(Builder $q) =>
                $q->where('slug', $categorySlug)
            )
            ->inCity($city);
    }

    public function getSeoSlug(Provider $provider): string
    {
        $categorySlug = $provider->category->slug;
        $city = \Illuminate\Support\Str::slug($provider->city);
        return "{$categorySlug}-{$city}";
    }
}

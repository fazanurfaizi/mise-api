<?php

namespace App\Actions\Products\Product;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class GetProducts
{
    use AsAction;

    public function handle(Request $request, bool $onlyTrashed = false)
    {
        return QueryBuilder::for(Product::class)
            ->allowedFields(['id', 'name', 'slug', 'description'])
            ->allowedFilters(['name', 'slug'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->when($onlyTrashed, fn($query) => $query->onlyTrashed())
            ->whereHas('categories', function($query) use ($request) {
                $query->when($request->get('category_id'), fn($q) =>
                    $q->where('product_categories.id', $request->get('category_id'))
                );
            })
            ->jsonPaginate();
    }
}

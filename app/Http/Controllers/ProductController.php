<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Transformers\ProductTransformer;

use Spatie\QueryBuilder\QueryBuilder;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = QueryBuilder::for(Product::class)
            ->allowedSorts('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFields('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFilters('id', 'name', 'free_shipping', 'description', 'price')
            ->jsonPaginate();

        $products = $paginator->getCollection();

        return fractal()
            ->collection($products)
            ->transformWith(new ProductTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->withResourceName('products')
            ->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $product = QueryBuilder::for(Product::class)
            ->allowedSorts('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFields('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFilters('id', 'name', 'free_shipping', 'description', 'price')
            ->find($id);

        return fractal()
            ->item($product)
            ->transformWith(new ProductTransformer())
            ->withResourceName('products')
            ->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->input('data.attributes', []));

        $product->save();

        return fractal()
            ->item($product)
            ->transformWith(new ProductTransformer())
            ->withResourceName('products')
            ->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response('', 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Http\Requests\SheetRequest;
use App\Jobs\ProcessSheet;
use App\Transformers\SheetTransformer;
use Illuminate\Contracts\Bus\Dispatcher;
use Spatie\QueryBuilder\QueryBuilder;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = QueryBuilder::for(Sheet::class)
            ->allowedSorts('id', 'status', 'file')
            ->allowedFields('id', 'status', 'file')
            ->allowedFilters('id', 'status', 'file')
            ->jsonPaginate();

        $sheets = $paginator->getCollection();

        return fractal()
            ->collection($sheets)
            ->transformWith(new SheetTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->withResourceName('sheet')
            ->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SheetRequest $request, Dispatcher $dispatcher)
    {
        $sheet = new Sheet();

        $sheet->status = Sheet::PENDING;

        $sheet->file = $request->file('file')->store('sheets');

        $sheet->save();

        $dispatcher->dispatch(new ProcessSheet($sheet));

        return fractal()
            ->item($sheet)
            ->transformWith(new SheetTransformer())
            ->withResourceName('sheet')
            ->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $sheet = QueryBuilder::for(Sheet::class)
            ->allowedSorts('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFields('id', 'name', 'free_shipping', 'description', 'price')
            ->allowedFilters('id', 'name', 'free_shipping', 'description', 'price')
            ->find($id);

        return fractal()
            ->item($sheet)
            ->transformWith(new SheetTransformer())
            ->withResourceName('sheet')
            ->respond();
    }
}

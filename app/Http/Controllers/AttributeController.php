<?php

namespace App\Http\Controllers;

use App\Facades\Search;
use App\Http\Requests\AttributeRequest;
use App\Http\Resources\NameWithIdResource;
use App\Models\Attribute;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return NameWithIdResource::collection(
            Attribute::latest('id')
                ->where(function($query){
                    Search::searchForHandle(
                        $query,
                        ['name'] ,
                        request('handle'),
                    );
                })
                ->paginate(paginationCountPerPage())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request): JsonResponse
    {
        $attribute = Attribute::create($request->validated());

        return $this->createdResponse(new NameWithIdResource($attribute));
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute): JsonResponse
    {
        return $this->resourceResponse(new NameWithIdResource($attribute));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute): JsonResponse
    {
        $attribute->name = $request->input('name');
        $attribute->save();

        return $this->successResponse(
            new NameWithIdResource($attribute) ,
            translateSuccessMessage('attribute' , 'updated')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        $attribute->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('attribute' , 'deleted')
        );
    }
}

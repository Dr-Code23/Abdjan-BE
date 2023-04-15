<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    use HttpResponse;
    public function index()
    {

            return ContactUsResource::collection(
                ContactUs::where(function($query){
                    Search::searchForHandle(
                        $query ,
                        ['email' , 'phone' , 'name' , 'end_date'] ,
                        request('handle')
                    );
                })
                    -> paginate(paginationCountPerPage())
        );
    }

    public function store(ContactUsRequest $request): \Illuminate\Http\JsonResponse
    {

        ContactUs::insert($request->validated());

        return $this->successResponse(
            msg:translateSuccessMessage('message' , 'sent')
        );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactsRequest;
use App\Services\ContactService;
use Illuminate\Support\Facades\DB;

class CreateContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function create()
    {
        return view('contacts.create');
    }    
    
    public function store(ContactsRequest $contactsRequest)
    {
        $data = $contactsRequest->validated();

        DB::beginTransaction();

        try {
            $this->contactService->createContact($data);

            DB::commit();

            return redirect()->route('contacts.create')
                ->with('success', 'Contact created successfully!')
                ->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('contacts.create')
                ->with('error', 'Error creating contact: ' . $e->getMessage())
                ->withInput();
        }
    }
}

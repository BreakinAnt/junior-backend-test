<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactsRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CreateContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $contacts = $this->contactService->getPagination(10);

        return Inertia::render('Contacts/Index', compact(['contacts']));
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

            return redirect()->route('contacts.index')
                ->with('success', 'Contact created successfully!')
                ->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.create')
                ->with('error', 'Error creating contact: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(int $id)
    {
        $contact = $this->contactService->getContactById($id);
        
        if (!$contact) {
            return redirect()->route('contacts.index')
                ->with('error', 'Contact not found.');
        }

        return view('contacts.edit', compact('contact'));
    }

    public function update(ContactsRequest $contactsRequest, int $id)
    {
        $data = $contactsRequest->validated();

        DB::beginTransaction();

        try {
            $contact = $this->contactService->updateContact($id, $data);
            
            if (!$contact) {
                DB::rollBack();
                return redirect()->route('contacts.index')
                    ->with('error', 'Contact not found.');
            }

            DB::commit();

            return redirect()->route('contacts.index')
                ->with('success', 'Contact updated successfully!')
                ->setStatusCode(200);;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.edit', $id)
                ->with('error', 'Error updating contact: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();

        try {
            $deleted = $this->contactService->deleteContact($id);
            
            if (!$deleted) {
                DB::rollBack();
                return redirect()->route('contacts.index')
                    ->with('error', 'Contact not found.');
            }

            DB::commit();

            return redirect()->route('contacts.index')
                ->with('success', 'Contact deleted successfully!')
                ->setStatusCode(200);;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.index')
                ->with('error', 'Error deleting contact: ' . $e->getMessage());
        }
    }
}

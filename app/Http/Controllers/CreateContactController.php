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

    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $contacts = $this->contactService->getPaginationWithFilters(10, $search, $sort, $direction);

        return Inertia::render('Contacts/Index', compact(['contacts', 'search', 'sort', 'direction']));
    }

    public function create()
    {
        return Inertia::render('Contacts/Create');
    }    
    
    public function store(ContactsRequest $contactsRequest)
    {
        $data = $contactsRequest->validated();

        DB::beginTransaction();

        try {

            $this->contactService->createContact($data);

            DB::commit();
            
            return Inertia::render('Contacts/Create')->toResponse(\request())->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return back()->withErrors([
                'message' => 'An unexpected error occurred while creating the contact. Please try again.'
            ])->withInput();
        }
    }

    public function edit(int $id)
    {
        $contact = $this->contactService->getContactById($id);
        
        if (!$contact) {
            return redirect()->route('contacts.index')
                ->with('error', 'Contact not found.');
        }

        return Inertia::render('Contacts/Create', compact('contact'));
    }

    public function update(ContactsRequest $contactsRequest, int $id)
    {
        $data = $contactsRequest->validated();

        DB::beginTransaction();

        try {
            
            $contact = $this->contactService->updateContact($id, $data);

            if (!$contact) {
                DB::rollBack();
                return back()->withErrors([
                    'message' => 'Contact not found.'
                ])->withInput();
            }

            DB::commit();
            
            return Inertia::render('Contacts/Create', compact('contact'))->toResponse(\request())->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return back()->withErrors([
                'message' => 'An unexpected error occurred while updating the contact. Please try again.'
            ])->withInput();
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();

        try {
            $deleted = $this->contactService->deleteContact($id);
            
            if (!$deleted) {
                DB::rollBack();
                return to_route('contacts.index')
                    ->with('error', 'Contact not found.');
            }

            DB::commit();

            return redirect()->route('contacts.index')
                ->with('success', 'Contact moved to trash successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.index')
                ->with('error', 'Error deleting contact: ' . $e->getMessage());
        }
    }

    public function trash(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $contacts = $this->contactService->getTrashedPaginationWithFilters(10, $search, $sort, $direction);

        return Inertia::render('Contacts/Trash', compact('contacts', 'search', 'sort', 'direction'));
    }

    public function restore(int $id)
    {
        DB::beginTransaction();

        try {
            $restored = $this->contactService->restoreContact($id);
            
            if (!$restored) {
                DB::rollBack();
                return redirect()->route('contacts.trash')
                    ->with('error', 'Contact not found in trash.');
            }

            DB::commit();

            return redirect()->route('contacts.trash')
                ->with('success', 'Contact restored successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.trash')
                ->with('error', 'Error restoring contact: ' . $e->getMessage());
        }
    }

    public function forceDelete(int $id)
    {
        DB::beginTransaction();

        try {
            $deleted = $this->contactService->forceDeleteContact($id);
            
            if (!$deleted) {
                DB::rollBack();
                return redirect()->route('contacts.trash')
                    ->with('error', 'Contact not found in trash.');
            }

            DB::commit();

            return redirect()->route('contacts.trash')
                ->with('success', 'Contact permanently deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            
            return redirect()->route('contacts.trash')
                ->with('error', 'Error permanently deleting contact: ' . $e->getMessage());
        }
    }
}

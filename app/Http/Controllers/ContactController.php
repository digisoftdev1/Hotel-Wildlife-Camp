<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display the contact management page.
     */
    public function index(): View
    {
        $contact = $this->contactService->getContact();
        return view('contact', compact('contact'));
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        try {
            $this->contactService->createContact($request->validated());
            return redirect()->back()->with('success', 'Contact details saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while saving contact details.');
        }
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        try {
            $this->contactService->updateContact($contact, $request->validated());
            return redirect()->back()->with('success', 'Contact details updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating contact details.');
        }
    }
}

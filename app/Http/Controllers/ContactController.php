<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactPostRequest;
use App\Http\Requests\ContactPutRequest;
class ContactController extends Controller
{
    /**
     * Display a paginated list of contacts with their phone numbers.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Eager load phone numbers to optimize database queries
        $contacts =  Contact::with('phoneNumbers')->paginate(5);

        return view('pages.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages.contacts.create');
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param ContactPostRequest $request The validated request containing contact details
     * @return RedirectResponse Redirects to show page on success, back with errors on failure
     * @throws \Throwable When database transaction fails
     */
    public function store(ContactPostRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $contact = null;

            DB::transaction(function () use ($validated, &$contact) {
                $contact = new Contact();
                $contact->fill($validated);
                $contact->save();

                $contact->syncPhoneNumbers($validated['number']);
            });

            return redirect()->route('contacts.show', compact('contact'));
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified contact.
     *
     * @param Contact $contact The contact to display
     * @return \Illuminate\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function show(Contact $contact): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param Contact $contact The contact to edit
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View
     */
    public function edit(Contact $contact): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     * 
     * @param ContactPutRequest $request The validated request containing updated contact details
     * @param Contact $contact The contact to update
     * @return RedirectResponse Redirects to show page on success, back with errors on failure
     * @throws \Throwable When database transaction fails
     */
    public function update(ContactPutRequest $request, Contact $contact): RedirectResponse
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated, $contact) {
                $contact->fill($validated);

                // Delete phone numbers not present in the request
                $contact->phoneNumbers()->whereNotIn('number', $validated['number'])->delete();

                // Add new phone numbers
                $contact->syncPhoneNumbers($validated['number']);

                $contact->save();
            });

            return redirect()->route('contacts.show', compact('contact'));
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Delete the specified contact from storage.
     *
     * @param Contact $contact The contact to delete
     * @return RedirectResponse Redirects to contacts index page after deletion
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}

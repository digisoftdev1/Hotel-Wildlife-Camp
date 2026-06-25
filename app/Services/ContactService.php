<?php

namespace App\Services;

use App\Models\Contact;

class ContactService
{
    public function getContact()
    {
        return Contact::first();
    }

    public function createContact(array $data)
    {
        return Contact::create($data);
    }

    public function updateContact(Contact $contact, array $data)
    {
        $contact->update($data);
        return $contact;
    }
}

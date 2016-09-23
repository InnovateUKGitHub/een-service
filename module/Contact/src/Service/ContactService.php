<?php

namespace Contact\Service;

class ContactService extends AbstractEntity
{
    /**
     * @param array $account
     *
     * @return array
     */
    public function create($account)
    {
        $contact = new \stdClass();
        $contact->FirstName = $account['firstname'];
        $contact->LastName = $account['lastname'];
        $contact->Email = $account['email'];
        $contact->Phone = $account['phone'];
        $contact->MobilePhone = $account['mobile'];
        $contact->Company = $account['company'];
        $contact->CompanyNumber = $account['company-number'];
        $contact->CompanyPostcode = $account['company-postcode'];
        $contact->CompanyAddress = $account['company-address'];
        $contact->CompanyPhone = $account['company-phone'];
        $contact->CompanyWebsite = $account['company-website'];

        return $this->createEntity($contact, 'Contact');
    }
}

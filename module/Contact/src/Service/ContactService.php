<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

class ContactService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        // Step1 Create Account
        $account = new \stdClass();
        $account->Name = $data['company-name'];
        $account->Phone = $data['company-phone'];
        $account->Website = $data['website'];
        $account->Company_Registration_Number__c = $data['company-number'];

        $account->BillingStreet = $data['company-address'];
        $account->BillingPostalCode = $data['company-postcode'];
        $account->BillingCity = $data['company-city'];
        $account->BillingCountry = $data['company-country'];

        $account->ShippingStreet = $data['company-address'];
        $account->ShippingPostalCode = $data['company-postcode'];
        $account->ShippingCity = $data['company-city'];
        $account->ShippingCountry = $data['company-country'];

        $accountId = $this->createEntity($account, 'Account');
        if ($accountId instanceof ApiProblemResponse) {
            return $accountId;
        }

        // Step2 Create Contact
        // Todo Get Id from param and update Lead to Contact instead of create
        $contact = new \stdClass();
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Email = $data['email'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['mobile'];
        $contact->AccountId = $accountId;

        $contactId = $this->createEntity($contact, 'Contact');
        if ($contactId instanceof ApiProblemResponse) {
            // If problem during contact creation delete account
            $this->salesForce->delete([$accountId]);

            return $contactId;
        }

        return [
            'account' => $accountId,
            'contact' => $contactId,
        ];
    }
}

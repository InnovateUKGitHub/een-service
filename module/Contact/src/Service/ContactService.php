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
        $account = new \stdClass();
        $account->Name = $data['company-name'];
        $account->Phone = $data['company-phone'];
        $account->Company_Registration_Number__c = $data['company-number'];
        $account->BillingStreet = $data['company-address'];
        $account->BillingPostalCode = $data['company-postcode'];
        $account->BillingCity = $data['company-city'];
        $account->BillingCountry = $data['company-country'];
        $account->Website = $data['website'];

        $accountId = $this->createEntity($account, 'Account');
        if ($accountId instanceof ApiProblemResponse) {
            return $accountId;
        }

        $contact = new \stdClass();
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Email = $data['email'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['mobile'];
        $contact->AccountId = $accountId;

        $contactId = $this->createEntity($contact, 'Contact');
        if ($accountId instanceof ApiProblemResponse) {
            return $accountId;
        }

        return [
            'accountId' => $accountId,
            'contactId' => $contactId,
        ];
    }
}

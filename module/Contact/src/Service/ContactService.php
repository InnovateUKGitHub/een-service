<?php

namespace Contact\Service;

class ContactService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $accountReference = new \stdClass();
        $accountReference->MyExtID__c = 'SAP111111';

        $contact = new \stdClass();
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Email = $data['email'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['mobile'];
        $contact->Account = $accountReference;

        $account = new \stdClass();
        $account->MyExtID__c = 'SAP111111';
        $account->Name = $data['company-name'];
        $account->Phone = $data['company-phone'];
        $account->Company_Registration_Number__c = $data['company-number'];
        $account->BillingStreet = $data['company-address'];
        $account->BillingPostalCode = $data['company-postcode'];
        $account->BillingCity = $data['company-city'];
        $account->BillingCountry = $data['company-country'];
        $account->Website = $data['website'];

        $contact = $this->createObject($contact, 'Contact');
        $account = $this->createObject($account, 'Account');

        return $this->createEntities([$account, $contact]);
    }
}

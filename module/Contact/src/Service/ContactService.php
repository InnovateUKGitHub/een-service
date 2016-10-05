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
        $account->Name = $data['company_name'];
        $account->Phone = $data['company_phone'];
        $account->Website = $data['website'];
        $account->Company_Registration_Number__c = $data['company_number'];

        $account->BillingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $account->BillingPostalCode = $data['postcode'];
        $account->BillingCity = $data['city'];
//        $account->BillingCountry = $data['county'];

        $account->ShippingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $account->ShippingPostalCode = $data['postcode'];
        $account->ShippingCity = $data['city'];
//        $account->ShippingCountry = $data['county'];

        $accountResponse = $this->createEntity($account, 'Account');
        if ($accountResponse instanceof ApiProblemResponse) {
            return $accountResponse;
        }

        // Step2 Create Contact
        // Todo Get Id from param and update Lead to Contact instead of create
        $contact = new \stdClass();
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Email = $data['email'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['contact_phone'];
        $contact->Email1__c = $data['contact_email'];
        $contact->Email_Address_2__c = $data['other_email'];

        $contact->MailingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $contact->MailingPostalCode = $data['postcode'];
        $contact->MailingCity = $data['city'];
//        $contact->MailingCountry = $data['county'];

        if (!empty($data['newsletter'])) {
            $contact->Email_Newsletter__c = true;
        }
        $contact->AccountId = $accountResponse;

        $contactResponse = $this->createEntity($contact, 'Contact');
        if ($contactResponse instanceof ApiProblemResponse) {
            // If problem during contact creation delete account
            $this->salesForce->delete([$accountResponse]);

            return $contactResponse;
        }

        return [
            'account' => $accountResponse,
            'contact' => $contactResponse,
        ];
    }

    public function describe($type)
    {
        return $this->salesForce->describesObject($type);
    }
}

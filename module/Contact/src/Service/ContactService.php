<?php

namespace Contact\Service;

use ZF\ApiProblem\ApiProblemResponse;

class ContactService extends AbstractEntity
{
    /**
     * @param SalesForceService $salesForce
     */
    public function __construct(SalesForceService $salesForce)
    {
        parent::__construct($salesForce);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $contact = $this->isContactExists($data['email']);

        if ($contact['type'] === 'Lead') {
            return $this->updateContact($contact['id'], $data);
        }
        return $contact;
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return array
     */
    public function updateContact($id, $data)
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

        $account->ShippingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $account->ShippingPostalCode = $data['postcode'];
        $account->ShippingCity = $data['city'];

        $accountResponse = $this->createEntity($account, 'Account');
        if ($accountResponse instanceof ApiProblemResponse) {
            return $accountResponse;
        }

        // Step2 Update Contact
        $contact = new \stdClass();
        $contact->Id = $id;
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['contact_phone'];
        $contact->Email1__c = $data['contact_email'];
        $contact->Email_Address_2__c = $data['other_email'];

        $contact->MailingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $contact->MailingPostalCode = $data['postcode'];
        $contact->MailingCity = $data['city'];

        if (!empty($data['newsletter'])) {
            $contact->Email_Newsletter__c = true;
        }
        $contact->AccountId = $accountResponse;
        $contact->Contact_Status__c = 'Client';

        $contactResponse = $this->updateEntity($contact, 'Contact');
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

    /**
     * @param string $type
     *
     * @return array
     */
    public function describe($type)
    {
        return $this->salesForce->describesObject($type);
    }
}

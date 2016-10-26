<?php

namespace Contact\Service;

use Common\Exception\SoapException;

class ContactService extends AbstractEntity
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $contact = $this->getContact($data['email']);
        $contactId = isset($contact['records']['Id']) ? $contact['records']['Id'] : null;
        $accountId = (isset($contact['records']['Account']['Id']) ? $contact['records']['Account']['Id'] : null);

        return $this->createUser(
            $data,
            $contactId,
            $accountId
        );
    }

    /**
     * @param string $data
     * @param string $contactId
     * @param string $accountId
     *
     * @return array
     * @throws SoapException
     */
    private function createUser($data, $contactId, $accountId)
    {
        $account = $this->createAccount($data, $accountId);

        try {
            $this->createContact($data, $contactId, $account['id']);
        } catch (SoapException $e) {
            $this->salesForce->delete([$account['id']]);
            throw $e;
        }

        return $this->getContact($data['email']);
    }

    /**
     * @param array  $data
     * @param string $accountId
     *
     * @return array
     */
    private function createAccount($data, $accountId)
    {
        $account = new \stdClass();
        if ($accountId !== null) {
            $account->Id = $accountId;
        }
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

        if ($accountId === null) {
            return $this->createEntity($account, 'Account');
        }

        return $this->updateEntity($account, 'Account');
    }

    /**
     * @param array  $data
     * @param string $contactId
     * @param string $accountId
     *
     * @return array
     */
    private function createContact($data, $contactId, $accountId)
    {
        $contact = new \stdClass();
        if ($contactId !== null) {
            $contact->Id = $contactId;
        }
        $contact->Id = $contactId;
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['contact_phone'];
        $contact->Email = $data['contact_email'];

        $contact->MailingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $contact->MailingPostalCode = $data['postcode'];
        $contact->MailingCity = $data['city'];

        if (!empty($data['newsletter'])) {
            $contact->Email_Newsletter__c = true;
        }
        $contact->AccountId = $accountId;
        $contact->Contact_Status__c = 'Client';

        if ($contactId === null) {
            $contact->Email1__c = $data['email'];

            return $this->createEntity($contact, 'Contact');
        }

        return $this->updateEntity($contact, 'Contact');
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

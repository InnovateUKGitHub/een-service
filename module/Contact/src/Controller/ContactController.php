<?php

namespace Contact\Controller;

use Contact\Service\ContactService;
use Zend\Http\Response;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class ContactController extends AbstractRestfulController
{
    /** @var ContactService */
    private $service;

    /**
     * ContactController constructor.
     *
     * @param ContactService $service
     */
    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $params = $this->getInputFilter()->getValues();

        $contact = $this->service->create($params);

        return $contact['records'];
    }

    /**
     * @param string $email
     *
     * @return array
     * @throws \Exception
     */
    public function get($email)
    {
        $contact = $this->service->getContact($email);
        if ($contact['size'] === 0) {
            throw new \Exception('User not found', Response::STATUS_CODE_404);
        }

        return $contact['records'];
    }
}

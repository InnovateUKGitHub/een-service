<?php

namespace Contact\Factory\Service;

use Contact\Service\ContactService;
use Zend\ServiceManager\ServiceManager;
use Zend\Soap\Client;

final class ContactServiceFactory
{
    const CONFIG = 'config';
    const SALES_FORCE = 'sales-force';
    const LOGIN = 'login';
    const PASSWORD = 'password';
    const URL = 'url';

    /**
     * @param ServiceManager $serviceManager
     *
     * @return ContactService
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $config = $serviceManager->get(self::CONFIG);

        $method = 'login';

        #request
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="urn:microsoft-dynamics-schemas/codeunit/WebServices">
     <soapenv:Header/>
     <soapenv:Body>
        <web:' . $method . '>
           SOAP REQUEST
        </web:' . $method . '>
     </soapenv:Body>
  </soapenv:Envelope>';

        $ch = curl_init();

        $headers = [
            'Method: POST',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "urn:microsoft-dynamics-schemas/codeunit/WebServices:' . $method . '"',
        ];

        curl_setopt($ch, CURLOPT_URL, __DIR__ . '/../../../../../config/SF_EEN_Enterprise.wsdl');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, "DOMAIN\\USERNAME:PASSWORD");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $output = curl_exec($ch);

        var_dump($output);
        die;
        $soap = new Client(
            __DIR__ . '/../../../../../config/SF_EEN_Enterprise.wsdl',
            [
                'login'    => $config[self::SALES_FORCE][self::LOGIN],
                'password' => $config[self::SALES_FORCE][self::PASSWORD],
                'uri'      => $config[self::SALES_FORCE][self::URL],
            ]
        );

        return new ContactService($soap);
    }
}
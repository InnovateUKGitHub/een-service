<?php

namespace Console\Service\Import;

use Console\Service\IndexService;
use Console\Service\Merlin\EventMerlin;
use Console\Validator\MerlinValidator;

class EventService
{
    /** @var IndexService */
    private $indexService;
    /** @var MerlinValidator */
    private $merlinValidator;
    /** @var EventMerlin */
    private $merlinData;
    /** @var array */
    private $structure;

    /**
     * EventService constructor.
     *
     * @param IndexService    $indexService
     * @param EventMerlin     $merlinData
     * @param MerlinValidator $merlinValidator
     * @param array           $structure
     */
    public function __construct(
        IndexService $indexService,
        EventMerlin $merlinData,
        MerlinValidator $merlinValidator,
        $structure
    )
    {
        $this->indexService = $indexService;
        $this->merlinData = $merlinData;
        $this->merlinValidator = $merlinValidator;
        $this->structure = $structure;
    }

    public function import($month, $type)
    {
        $results = $this->merlinData->getList($month, $type);

        $this->indexService->createIndex(ES_INDEX_EVENT);

        $this->merlinValidator->checkEventsExists($results);

        $dateImport = (new \DateTime())->format('Ymd');
        foreach ($results->{'event'} as $event) {
            $this->merlinValidator->checkDataExists($event, $this->structure);

            $params = [
                'title'                  => (string)$event->{'EventTitle'}->__toString() ?: null,
                'start_date'             => (string)$event->{'EventStartDate'}->__toString() ?: null,
                'end_date'               => (string)$event->{'EventEndDate'}->__toString() ?: null,
                'closing_date'           => (string)$event->{'EventClosingDate'}->__toString() ?: null,
                'contact_attributes'     => (string)$event->{'ContactAttributes'}->__toString() ?: null,
                'description'            => (string)$event->{'Description'}->__toString() ?: null,
                'type'                   => (string)$event->{'EventType'}->__toString() ?: null,
                'style'                  => (string)$event->{'EventStyle'}->__toString() ?: null,
                'event_status'           => (string)$event->{'EventStatus'}->__toString() ?: null,
                'host_organisation'      => (string)$event->{'HostOrganisation'}->__toString() ?: null,
                'country_code'           => (string)$event->{'CountryISO'}->__toString() ?: null,
                'country'                => (string)$event->{'Country'}->__toString() ?: null,
                'city'                   => (string)$event->{'City'}->__toString() ?: null,
                'preliminary_text'       => (string)$event->{'Preliminarytext'}->__toString() ?: null,
                'deadline'               => (string)$event->{'DeadlineForRegistering'}->__toString() ?: null,
                'location_city'          => (string)$event->{'LocationDetailsCity'}->__toString() ?: null,
                'location_country'       => (string)$event->{'LocationDetailsCountry'}->__toString() ?: null,
                'location_name'          => (string)$event->{'LocationDetailsName'}->__toString() ?: null,
                'location_address'       => (string)$event->{'LocationDetailsEventAddress'}->__toString() ?: null,
                'location_phone'         => (string)$event->{'LocationDetailsContactTelephone'}->__toString() ?: null,
                'location_fax'           => (string)$event->{'LocationDetailsContactFax'}->__toString() ?: null,
                'location_website'       => (string)$event->{'LocationDetailsWebPage'}->__toString() ?: null,
                'location_contact_name'  => (string)$event->{'LocationContactName'}->__toString() ?: null,
                'location_contact_fax'   => (string)$event->{'LocationContactFax'}->__toString() ?: null,
                'location_contact_phone' => (string)$event->{'LocationContactTelephone'}->__toString() ?: null,
                'location_contact_email' => (string)$event->{'LocationContactEmail'}->__toString() ?: null,
                'created'                => (string)$event->{'Created'}->__toString() ?: null,
                'status'                 => (string)$event->{'Status'}->__toString() ?: null,
                'contact_name'           => (string)$event->{'ContactName'}->__toString() ?: null,
                'contact_phone'          => (string)$event->{'ContactTelephone'}->__toString() ?: null,
                'contact_fax'            => (string)$event->{'ContactFax'}->__toString() ?: null,
                'contact_email'          => (string)$event->{'ContactEmail'}->__toString() ?: null,
                'een_partner'            => (string)$event->{'NameOfEENPartner'}->__toString() ?: null,
                'date_import'            => $dateImport,
            ];

            $this->indexService->index(
                $params,
                microtime(),
                ES_INDEX_EVENT,
                ES_TYPE_EVENT
            );
        }
    }

    public function delete($since, \DateTime $now)
    {
    }
}
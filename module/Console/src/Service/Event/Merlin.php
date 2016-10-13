<?php

namespace Console\Service\Event;

use Common\Constant\EEN;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;

class Merlin
{
    /** @var IndexService */
    private $indexService;
    /** @var MerlinConnection */
    private $merlinData;
    /** @var MerlinValidator */
    private $merlinValidator;
    /** @var array */
    private $structure;

    /**
     * MerlinIngest constructor.
     *
     * @param IndexService     $indexService
     * @param MerlinConnection $merlinData
     * @param MerlinValidator  $merlinValidator
     * @param array            $structure
     */
    public function __construct(
        IndexService $indexService,
        MerlinConnection $merlinData,
        MerlinValidator $merlinValidator,
        $structure
    )
    {
        $this->indexService = $indexService;
        $this->merlinData = $merlinData;
        $this->merlinValidator = $merlinValidator;
        $this->structure = $structure;
    }

    /**
     * @param \DateTime $dateImport
     */
    public function import($dateImport)
    {
        $events = $this->merlinData->getList();
        $this->merlinValidator->checkEventsExists($events);

        foreach ($events->{'event'} as $event) {
            $this->merlinValidator->checkDataExists($event, $this->structure);

            $url = (string)$event->{'ContactAttributes'}->__toString() ?:
                ((string)$event->{'location_website'}->__toString() ?: null);

            if (!empty($url)) {
                $params = [
                    'title'        => (string)$event->{'EventTitle'}->__toString() ?: null,
                    'description'  => (string)$event->{'Description'}->__toString() ?: null,
                    'start_date'   => (string)$event->{'EventStartDate'}->__toString() ?: null,
                    'end_date'     => (string)$event->{'EventEndDate'}->__toString() ?: null,
                    'country_code' => (string)$event->{'CountryISO'}->__toString() ?: null,
                    'country'      => (string)$event->{'Country'}->__toString() ?: null,
                    'city'         => (string)$event->{'City'}->__toString() ?: null,
                    'url'          => $url,
                    'type'         => 'merlin',
                    'date_import'  => $dateImport,
                ];

                // No id specified by merlin so have to use the date creation
                $id = sha1((string)$event->{'Created'}->__toString());

                $this->indexService->index(
                    $params,
                    $id,
                    EEN::ES_INDEX_EVENT,
                    EEN::ES_TYPE_EVENT
                );
            }
        }
    }
}
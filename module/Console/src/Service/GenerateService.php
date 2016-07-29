<?php

namespace Console\Service;

use Faker\Generator;

class GenerateService
{
    const OPPORTUNITY = 'opportunity';
    const EVENT = 'event';
    const ALL = 'all';

    /** @var IndexService */
    private $indexService;
    /** @var Generator */
    private $faker;

    /**
     * GenerateService constructor.
     *
     * @param IndexService $indexService
     * @param Generator    $faker
     */
    public function __construct(IndexService $indexService, Generator $faker)
    {
        $this->indexService = $indexService;
        $this->faker = $faker;
    }

    /**
     * @param string $index
     * @param int    $number
     */
    public function generate($index, $number)
    {
        switch ($index) {
            case self::OPPORTUNITY:
                $this->generateOpportunities($number);
                break;
            case self::EVENT:
                $this->generateEvents($number);
                break;
            case self::ALL:
            default:
                $this->generateOpportunities($number);
                $this->generateEvents($number);
        }
    }

    /**
     * @param int $number
     */
    private function generateOpportunities($number)
    {
        $this->indexService->createIndex(IndexService::ES_INDEX_OPPORTUNITY);

        for ($i = 0; $i < $number; $i++) {
            $params = [
                'id'               => $this->faker->bothify('???##########'),
                'name'             => $this->faker->name,
                'type'             => $this->faker->randomElement(['Request', 'Offering']),
                'opportunity_type' => $this->faker->randomElement(['Technology', 'Commercial', 'Research']),
                'country'          => $this->faker->countryCode,
                'date'             => $this->generateDate($this->faker->dateTimeBetween('-12 months', 'now')),
                'types'            => $this->generateRandomArray(),
                'description'      => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'expertise'        => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'advantage'        => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'stage'            => $this->faker->sentence($this->faker->numberBetween(1, 5)),
                'stage_reference'  => $this->faker->sentence,
                'ipr'              => $this->faker->sentence($this->faker->numberBetween(1, 5)),
                'ipr_reference'    => $this->faker->sentence($this->faker->numberBetween(1, 5)),
            ];

            $this->indexService->index(
                $params,
                IndexService::ES_INDEX_OPPORTUNITY,
                IndexService::ES_TYPE_OPPORTUNITY,
                $params['id']
            );
        }
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    private function generateDate(\DateTime $date)
    {
        return $date->format(\DateTime::W3C);
    }

    /**
     * @return array
     */
    private function generateRandomArray()
    {
        $number = $this->faker->numberBetween(1, 5);
        $types = [];
        for ($i = 0; $i < $number; $i++) {
            $types[] = $this->faker->sentence($this->faker->numberBetween(1, 5));
        }

        return $types;
    }

    /**
     * @param int $number
     */
    private function generateEvents($number)
    {
        $this->indexService->createIndex(IndexService::ES_INDEX_EVENT);

        for ($i = 0; $i < $number; $i++) {
            $params = [
                'id'          => $i + 1,
                'name'        => $this->faker->name,
                'type'        => $this->faker->randomElement(['Seminar', 'Brokerage Event', 'Match-making Event', 'Conference']),
                'place'       => $this->faker->country,
                'address'     => $this->faker->address,
                'latitude'    => $this->faker->latitude,
                'longitude'   => $this->faker->longitude,
                'date_from'   => $this->generateDate($this->faker->dateTimeBetween('now', '+2 days')),
                'date_to'     => $this->generateDate($this->faker->dateTimeBetween('+2 days', '+5 days')),
                'description' => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'attendee'    => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'agenda'      => $this->faker->paragraph($this->faker->numberBetween(1, 15)),
                'cost'        => $this->faker->sentence,
                'topics'      => $this->generateRandomArray(),
            ];

            $this->indexService->index(
                $params,
                IndexService::ES_INDEX_EVENT,
                IndexService::ES_TYPE_EVENT,
                $params['id']
            );
        }
    }
}

<?php
namespace Console\Service;

use Console\Validator\MerlinValidator;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;
use Zend\Log\Logger;

class ImportService
{
    const USERNAME = 'username';

    const PASSWORD = 'password';

    const PATH_GET_PROFILE = 'path-get-profile';

    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $path;
    /** @var HttpService */
    private $client;
    /** @var IndexService */
    private $indexService;
    /** @var MerlinValidator */
    private $merlinValidator;
    /** @var Logger */
    private $logger;

    /**
     * ImportService constructor.
     *
     * @param HttpService     $client
     * @param IndexService    $indexService
     * @param MerlinValidator $merlinValidator
     * @param Logger          $logger
     * @param array           $config
     */
    public function __construct(HttpService $client, IndexService $indexService, MerlinValidator $merlinValidator, Logger $logger, $config)
    {
        $this->indexService = $indexService;
        $this->merlinValidator = $merlinValidator;
        $this->client = $client;
        $this->username = $config[self::USERNAME];
        $this->password = $config[self::PASSWORD];
        $this->path = $config[self::PATH_GET_PROFILE];
        $this->logger = $logger;
    }

    /**
     * @param string    $since
     * @param \DateTime $now
     */
    public function delete($since, \DateTime $now)
    {
        $results = $this->indexService->getAll();

        $dateImport = $now->format('Ymd');
        $dateSince = $now->sub(new \DateInterval('P' . $since . 'M'))->format('Ymd');
        $body = [];
        foreach ($results['hits']['hits'] as $document) {
            if ($document['_source']['date_import'] < $dateImport ||
                $document['_source']['date'] < $dateSince
            ) {
                $body['body'][] = [
                    'delete' => [
                        '_index' => ES_INDEX_OPPORTUNITY,
                        '_type'  => ES_TYPE_OPPORTUNITY,
                        '_id'    => $document['_source']['id'],
                    ],
                ];
            }
        }

        if (empty($body)) {
            return;
        }

        $this->indexService->delete($body);
    }

    /**
     * @param string $month
     *
     * @return null
     */
    public function import($month)
    {
        $this->importOpportunities($this->getData($month));
    }

    /**
     * @param string $month
     *
     * @return \SimpleXMLElement|null
     */
    private function getData($month)
    {
        $this->client->setHttpMethod(Request::METHOD_GET);
        $this->client->setPathToService($this->path);
        $this->client->setQueryParams($this->buildQuery($month));

        try {
            return simplexml_load_string($this->client->execute(false));
        } catch (HttpException $e) {
            $this->logger->debug("An error occurred during the retrieve of the $month month");
            $this->logger->debug($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->debug("An error occurred during the retrieve of the $month month");
            $this->logger->debug($e->getMessage());
        }

        throw new \RuntimeException("An error occurred during the retrieve of the $month month");
    }

    /**
     * @param string $month
     *
     * @return array
     */
    private function buildQuery($month)
    {
        $return = [];
        if (empty($this->username) === false) {
            $return['u'] = $this->username;
        }
        if (empty($this->password) === false) {
            $return['p'] = $this->password;
        }

        $return['sb'] = (new \DateTime())->sub(new \DateInterval('P' . ($month - 1) . 'M'))->format('Ymd');
        $return['sa'] = (new \DateTime())->sub(new \DateInterval('P' . ($month) . 'M'))->format('Ymd');

        return $return;
    }

    /**
     * @param \SimpleXMLElement $results
     */
    private function importOpportunities(\SimpleXMLElement $results)
    {
        $this->indexService->createIndex(ES_INDEX_OPPORTUNITY);

        $this->merlinValidator->checkProfilesExists($results);

        $dateImport = (new \DateTime())->format('Ymd');
        foreach ($results->{'profile'} as $profile) {

            $this->merlinValidator->checkProfileDataExists($profile);

            $reference = $profile->{'reference'};
            $content = $profile->{'content'};
            $cooperation = $profile->{'cooperation'};
            $company = $profile->{'company'};
            $datum = $profile->{'datum'};
            $keyword = $profile->{'keyword'};

            $id = (string)$reference->{'external'}->__toString();

            $params = [
                'id'                 => $id,
                'type'               => (string)$reference->{'type'}->__toString(),
                'title'              => (string)$content->{'title'}->__toString(),
                'summary'            => (string)$content->{'summary'}->__toString(),
                'description'        => (string)$content->{'description'}->__toString(),
                'partner_expertise'  => (string)$cooperation->{'partner'}->{'area'}->__toString(),
                'stage'              => (string)$cooperation->{'stagedev'}->{'stage'}->__toString(),
                'ipr'                => (string)$cooperation->{'ipr'}->{'status'}->__toString(),
                'ipr_comment'        => (string)$cooperation->{'ipr'}->{'comment'}->__toString(),
                'country_code'       => (string)$company->{'country'}->{'key'}->__toString(),
                'country'            => (string)$company->{'country'}->{'label'}->__toString(),
                'date_create'        => (string)$datum->{'submit'}->__toString() ?: null,
                'date'               => (string)$datum->{'update'}->__toString() ?: null,
                'deadline'           => (string)$datum->{'deadline'}->__toString() ?: null,
                'partnership_sought' => $this->extractPartnerships($profile->{'partnerships'}),
                'industries'         => $this->extractIndustries($cooperation->{'exploitations'}),
                'technologies'       => $this->extractTechnologies($keyword->{'technologies'}),
                'commercials'        => $this->extractCommercials($keyword->{'naces'}),
                'markets'            => $this->extractMarkets($keyword->{'markets'}),
                'eoi'                => (bool)$profile->{'eoi'}->{'status'}->__toString(),
                'advantage'          => (string)$cooperation->{'plusvalue'}->__toString(),
                'date_import'        => $dateImport,
            ];

            $this->indexService->index(
                $params,
                $id,
                ES_INDEX_OPPORTUNITY,
                ES_TYPE_OPPORTUNITY
            );
        }
    }

    /**
     * @param \SimpleXMLElement $partnerships
     *
     * @return array
     */
    private function extractPartnerships(\SimpleXMLElement $partnerships)
    {
        $result = [];
        foreach ($partnerships->{'string'} as $partnership) {
            $result[] = (string)$partnership;
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $industries
     *
     * @return array
     */
    private function extractIndustries(\SimpleXMLElement $industries)
    {
        $result = [];
        foreach ($industries->{'exploitation'} as $industry) {
            if ((string)$industry->{'other'}) {
                $result[] = (string)$industry->{'other'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $technologies
     *
     * @return array
     */
    private function extractTechnologies(\SimpleXMLElement $technologies)
    {
        $result = [];
        foreach ($technologies->{'technology'} as $technology) {
            if ((string)$technology->{'label'}) {
                $result[] = (string)$technology->{'label'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $commercials
     *
     * @return array
     */
    private function extractCommercials(\SimpleXMLElement $commercials)
    {
        $result = [];
        foreach ($commercials->{'nace'} as $commercial) {
            if ((string)$commercial->{'label'}) {
                $result[] = (string)$commercial->{'label'};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $markets
     *
     * @return array
     */
    private function extractMarkets(\SimpleXMLElement $markets)
    {
        $result = [];
        foreach ($markets->{'market'} as $market) {
            if ((string)$market->{'label'}) {
                $result[] = (string)$market->{'label'};
            }
        }

        return $result;
    }
}

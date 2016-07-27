<?php
namespace Console\Service;

use Zend\Http\Request;

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
    private $type;
    /** @var string */
    private $path;
    /** @var HttpService */
    private $client;
    /** @var IndexService */
    private $indexService;

    public function __construct(HttpService $client, IndexService $indexService, $config)
    {
        $this->indexService = $indexService;
        $this->client = $client;
        $this->username = $config[self::USERNAME];
        $this->password = $config[self::PASSWORD];
        $this->path = $config[self::PATH_GET_PROFILE];
    }

    public function import($type)
    {
        $this->importOpportunities($this->getData($type));
    }

    public function getData($type)
    {
        if ($type !== 'all') {
            $this->type = $type;
        }
        $this->client->setHttpMethod(Request::METHOD_GET);
        $this->client->setPathToService($this->path);
        $this->client->setQueryParams($this->buildQuery());
        $result = simplexml_load_string($this->client->execute(false));

        return $result;
    }

    private function buildQuery()
    {
        $return = [];
        if (empty($this->type) === false) {
            $return['pt'] = $this->type;
        }
        if (empty($this->username) === false) {
            $return['u'] = $this->username;
        }
        if (empty($this->password) === false) {
            $return['p'] = $this->password;
        }
        $return['sa'] = (new \DateTime())->sub(new \DateInterval('P1M'))->format('Ymd');

        return $return;
    }

    public function importOpportunities(\SimpleXMLElement $results)
    {
        $this->indexService->createIndex(IndexService::ES_INDEX_OPPORTUNITY);

        foreach ($results->profile as $profile) {
            $params = [
                'id'                 => (string)$profile->reference->external,
                'title'              => (string)$profile->content->title,
                'summary'            => (string)$profile->content->summary,
                'description'        => (string)$profile->content->description,
                'partner_expertise'  => (string)$profile->cooperation->partner->area,
                'stage'              => (string)$profile->cooperation->stagedev->stage,
                'ipr'                => (string)$profile->cooperation->ipr->status,
                'ipr_comment'        => (string)$profile->cooperation->title->comment,
                'country_code'       => (string)$profile->company->country->key,
                'country'            => (string)$profile->company->country->label,
                'date'               => (string)$profile->datum->update,
                'deadline'           => (string)$profile->datum->deadline,
                'partnership_sought' => $this->extractPartnerships($profile->partnerships),
                'industries'         => $this->extractIndustries($profile->cooperation->exploitations),
                'technologies'       => $this->extractTechnologies($profile->keyword->technologies),
                'commercials'        => $this->extractCommercials($profile->keyword->naces),
                'markets'            => $this->extractMarkets($profile->keyword->markets),
                'eoi'                => (bool)$profile->eoi->status,
                'advantage'          => (string)$profile->cooperation->plusvalue,
            ];

            $this->indexService->index(
                $params,
                IndexService::ES_INDEX_OPPORTUNITY,
                IndexService::ES_TYPE_OPPORTUNITY,
                (string)$profile->reference->external
            );
        }
    }

    private function extractPartnerships($partnerships)
    {
        $result = [];
        foreach ($partnerships->string as $partnership) {
            $result[] = (string)$partnership;
        }

        return $result;
    }

    private function extractIndustries($industries)
    {
        $result = [];
        foreach ($industries->exploitation as $industry) {
            if ((string)$industry->label) {
                $result[] = (string)$industry->label;
            }
        }

        return $result;
    }

    private function extractTechnologies($technologies)
    {
        $result = [];
        foreach ($technologies->technologies as $technology) {
            if ((string)$technology->label) {
                $result[] = (string)$technology->label;
            }
        }

        return $result;
    }

    private function extractCommercials($commercials)
    {
        $result = [];
        foreach ($commercials->nace as $commercial) {
            if ((string)$commercial->label) {
                $result[] = (string)$commercial->label;
            }
        }

        return $result;
    }

    private function extractMarkets($markets)
    {
        $result = [];
        foreach ($markets->market as $market) {
            if ((string)$market->label) {
                $result[] = (string)$market->label;
            }
        }

        return $result;
    }
}

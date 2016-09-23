<?php
namespace Common\Constant;

class EEN
{
    const CONFIG = 'config';

    //CURL
    const CURL = 'curl-opt';
    const ENCODING = 'encoding';
    const MAX_CONNECTION = 'max-connection';
    const FRESH_CONNECTION = 'fresh-connection';
    const TIMEOUT = 'timeout';

    // API
    const GOV_DELIVERY = 'gov-delivery';
    const SERVER = 'server';
    const SCHEME = 'scheme';
    const TOKEN = 'token';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PATH_EVENT = 'path-event';
    const PATH_PROFILE = 'path-profile';

    const MERLIN = 'merlin';
    const MERLIN_EVENT_STRUCTURE = 'merlin-event-structure';
    const MERLIN_PROFILE_STRUCTURE = 'merlin-profile-structure';
    const EVENT_BRITE = 'event-brite';

    // ELASTIC SEARCH
    const ELASTIC_SEARCH_INDEXES = 'elastic-search-indexes';
    const ES_INDEX_OPPORTUNITY = 'opportunity';
    const ES_TYPE_OPPORTUNITY = 'opportunity';
    const ES_INDEX_EVENT = 'event';
    const ES_TYPE_EVENT = 'event';
}
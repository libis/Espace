<?php
/**
 * User: NaeemM
 */

require_once 'Zend/Rest/Client.php';
require_once 'Zend/Service/Exception.php';

class LibcoService {

    public $db;

    function __construct()
    {
        $this->db = get_db();
    }

    public function prepareUrl($searchType){
        $serverUrl = get_option('libco_server_url');
        $urlPath = get_option('libco_url_path');
        if(empty($serverUrl))
            return null;

        $reqType = null;
        switch ($searchType){
            case 'general':
                if(!empty($urlPath))
                    $endPoint = $urlPath;
                $reqType = "POST";
                break;

            case 'searchsources':
                $endPoint = "api/searchsources";
                $reqType = "GET";
                break;

            case 'collection':
                break;

        }
        if(!empty($endPoint))
            $requestUrl = get_option('libco_server_url').'/'.$endPoint;
        else
            $requestUrl = get_option('libco_server_url');

        return array('url' => $requestUrl, 'type' => $reqType);
    }


    public function search($qParameters, $sources, $page){
        $filters = array(array(
            array('fieldId' => "anywhere", 'value' =>$qParameters, "exact" => false),
            array('fieldId' => "resourceType", 'value' =>"CulturalObject", 'exact' => true)
        ));
        $reqBody = array(
            "filters" => $filters,
            "page" => $page,
            "pageSize" => 10,
            "sources" => $sources
        );
        $response = $this->makeRequest("general", $reqBody);
        $response = json_decode($response, true);
        return $response;
    }

    public function normalizeResult($result, $qParameters){
        $errorMessage = "";
        $numberofRecords = 0;
        $sourceResult = array();
        $highestTotal = 0;

        if(array_key_exists('error', $result) && strlen($result['error']) > 0)
        {
            $errorMessage = $result['error'];
        }
        else
        {
            foreach($result as $searchItem){
                if($searchItem['totalCount'] < 1)
                    continue;

                $sourceResult[$searchItem['source']] = $searchItem['items'];
                $numberofRecords += $searchItem['totalCount'];
                if($searchItem['totalCount'] > $highestTotal)
                    $highestTotal = $searchItem['totalCount'];
            }
            arsort($sourceResult);
        }

        //STATUS:
        /*
         * paging is working fine now but there are few issues from espace api side:
         * searches on Europeana source returns different totalCount which makes paging results inconsistent for this source.
         * Search on Rijksmuseum return different totalCount and number of items.
         *
         * Paging is calculated by summing up all totalCounts (which should remain consistent for all pages) and dividing
         * it by page size (which is 100 in this example. For instance, if Europeana, DigitalNZ, Mint, Rijksmuseum have
         * 306, 1145454, 45000 and 789546 totalCount subsequently, then, page size will be calculated based on the maximum totalCount
         * value which is 1145454 (of DigitalNZ). That is 1145454/100 or 11454,54, giving 11455 pages of size 100.
         * */
        $result = array(
            'query' => $qParameters,
            'records' => $sourceResult,
            'totalResults' => $numberofRecords,
            'error' => $errorMessage,
            'highestTotalRecords' => $highestTotal
        );
        return $result;
    }

    /**
     * @param null $userId user id to fetch a particular user's collections
     * @return array
     */
    public function getCollectionList($userId=null){
        $collections = array();

        $options = array();
        if(!empty($userId))
            $options = array('owner_id' => current_user()->id);

        $collectionTable = $this->db->getTable('Collection');
        $userCollections = $collectionTable->findBy($options);

        foreach($userCollections as $collection){
            $collectionTitle = metadata($collection, array("Dublin Core", "Title"));

            // skip if a collection is without title
            if(empty($collectionTitle))
                continue;

            /*
               skip if
                    1. a collection with same id already exists in the list
                    2. skip if a collection with same name already exists in the list
            */
            $key = array_search($collectionTitle, $collections);
            if(array_key_exists($collection->id, $collections) || !empty($key))
                continue;

            $collections [$collection->id.','.$collectionTitle] = $collectionTitle;
        }
        asort($collections); // sort collection array
        return $collections;
    }

    /**
     * Prepare and make http request.
     * @param $requestType
     * @param $requestBody
     * @param $recordKey
     * @return string|void
     * @throws Zend_Service_Exception
     */
    public function makeRequest($requestType, $requestBody){
        $request = $this->prepareUrl($requestType);
        if(empty($request['url']) || empty($request['type'])){
/*            $this->errorMessage($requestType, 'Error in preparing http request.');
            return;*/
            return json_encode(array('error' => 'Error in preparing http request.'));
        }

        $requestConfig = array(
            'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
            'proxy_host' => get_option('libco_server_proxy'),
            'timeout' => 900
        );

        $restClient = new Zend_Rest_Client();
        $httpClient = $restClient->getHttpClient();
        $httpClient->resetParameters();
        $httpClient->setUri($request['url']);
        $httpClient->setConfig($requestConfig);
        $httpClient->setHeaders('Content-Type', 'application/json');
        $httpClient->setRawData(json_encode($requestBody));
        $response = $httpClient->request($request['type']);
        if($response->getStatus() === 200)
            return $response->getBody();
        else
            return json_encode(array('error' => "http connection error: ".$response->getStatus().", ".$response->getMessage()));

    }

       /**
     * Converts omeka record to appropriate request body in json format.
     * @param $record
     * @return string
     */
    public function prepareRequestBody($record){
        $requestBody = array(
            'data' => array(
                'name' => $record->id.'.json',
                'type' => 'text/plain',
                'content' => serialize($record)
            )
        );

        return $requestBody;
    }

    public function getSearchSources(){
        $request = $this->prepareUrl("searchsources");
        if(empty($request['url']) || empty($request['type'])){
            return json_encode(array('error' => 'Error in preparing http request.'));
        }

        $requestConfig = array(
            'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
            'proxy_host' => get_option('libco_server_proxy'),
            'timeout' => 900
        );

        $restClient = new Zend_Rest_Client();
        $httpClient = $restClient->getHttpClient();
        $httpClient->resetParameters();
        $httpClient->setUri($request['url']);
        $httpClient->setConfig($requestConfig);
        $httpClient->setHeaders('Content-Type', 'application/json');
        $response = $httpClient->request($request['type']);
        if($response->getStatus() === 200){
            $resp = json_decode($response->getBody());
            return $resp;
        }
    }

    /**
     * Prepare and throw exception.
     * @param $requestType
     * @param $message
     * @throws Zend_Service_Exception
     */
    public function errorMessage($requestType, $message){
        throw new Zend_Service_Exception('An error occurred making '. $requestType .' request. Message: ' . $message);
    }

}

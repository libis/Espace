<?php

function libis_search_exhibits($params){
    $db = get_db();
    $records = $db->getTable('SearchText')->findBy($params);
    
    return $records;
}


<?php

function libis_search_exhibits($params){
    $db = get_db();
    
    if(!$params):
        $records = get_records('Exhibit',array('recent'=> true),50);
        return $records;
    endif;
    
    
    $records = $db->getTable('Exhibit')->findBy($params);    
    if(!$records):
        return 'No stories were found';
    endif;
    
    return $records;    
}


<?php

function libis_search_exhibits($params){
    $db = get_db();
    
    if(!$params):
        $records = get_records('Exhibit',array('recent'=> true),50);
        return $records;
    endif;
    
    $records = $db->getTable('SearchText')->findBy($params);    
        
    $exhibits= array();
    foreach($records as $record):
        if($record['record_type']=='Exhibit'):
            $exhibit = get_record_by_id('Exhibit', $record['record_id']);
            $exhibits[$record['record_id']]=$exhibit;
        endif;
    endforeach;
    
    return $exhibits;
}


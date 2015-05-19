<?php

class UserProfilesType extends Omeka_Record_AbstractRecord {

    public $id;
    public $label;
    public $description;
    public $element_set_id;
    public $required_element_ids;
    public $required_multielement_ids;
    public $public;
    public $required;
    private $_elements;
    private $_elementInfos;
    private $_multiInfos;
    protected $_related = array('ElementSet' => 'getElementSet', 'Elements'=>'getAllElements');    
    
    public function setArray($data)
    {
        $data['required_element_ids'] = unserialize($data['required_element_ids']);
        $data['required_multielement_ids'] = unserialize($data['required_multielement_ids']);
        parent::setArray($data);
    }

    protected function beforeSave($args) {
        $this->required_element_ids = array();
        $this->required_multielement_ids = array();
        foreach($this->_elementInfos as $elementInfo) {
            $element=$elementInfo['element'];
            $element->order = $elementInfo['order'];
            if (empty($elementInfo['description'])) {
                $element->description = '';
            } else {
                $element->description = $elementInfo['description'];
            }
            $element->save();
            $this->addErrorsFrom($element);
            if(isset($elementInfo['required']) && $elementInfo['required'] == 'on') {
                $this->required_element_ids[] = $element->id;
            }
        }
        foreach($this->_multiInfos as $multiInfo) {
            $multiEl = $multiInfo['element'];
            $multiEl->order = $multiInfo['order'];
            $multiEl->description = $multiInfo['description'];
            $multiEl->setOptions($multiInfo['options']);
            $multiEl->save();
            $this->addErrorsFrom($multiEl);
            if($multiInfo['required']) {
                $this->required_multielement_ids[] = $multiEl->id;
            }                
        }
        $this->required_element_ids = serialize($this->required_element_ids);
        $this->required_multielement_ids = serialize($this->required_multielement_ids);
    }
    
    public function getElementSet()
    {
        return $this->_db->getTable('ElementSet')->find($this->element_set_id);
    }
    
    /*
     * Do a weird splice of regular elements and UserProfilesMultiElements
     */
    
    public function getAllElements()
    {
        if(!$this->exists()) {
            return array();   
        }
        $elements = $this->ElementSet->getElements();
        $multiElements = $this->_db->getTable('UserProfilesMultiElement')->findBy(array('element_set_id'=>$this->element_set_id));
        $allElements = array_merge($elements, $multiElements);
        usort($allElements, array($this, '_sortElements')); 
        return $allElements;  
    }
    
    
    public function setElementInfos($elementInfos)
    {
        $this->_elementInfos = $elementInfos;
    }
    
    public function setMultiElementInfos($multiInfos)
    {
        $this->_multiInfos = $multiInfos;
    }
    
    public function requiredElement($element)
    {
        if(!is_array($this->required_element_ids)) {
            $this->required_element_ids = array();
        }
        
        if(!is_array($this->required_multielement_ids)) {
            $this->required_multielement_ids = array();
        }
        return (in_array($element->id, $this->required_element_ids) || in_array($element->id, $this->required_multielement_ids));
    }    
    
    protected function afterDelete()
    {
        $elSet = $this->getElementSet();
        if($elSet) {
            $elSet->delete();
        }
        $profilesToDelete = $this->getTable('UserProfilesProfile')->findBy(array('type_id' => $this->id));
        foreach($profilesToDelete as $profile) {
            $profile->deleteWithRelation();
        }                
    }
    
    protected function _validate()
    {
        if (empty($this->label)) {
            $this->addError('Label', __('Profile Type Name must be set'));
        }
        
        if (empty($this->description)) {
            $this->addError('Description', __('Profile Type Description must be set'));
        }
    }
    
    private function _sortElementInfos($a, $b)
    {
        if($a['order'] == $b['order']) {
            return 0;
        }
        
        return ($a['order'] < $b['order']) ? -1 : 1;        
    }
    
    private function _sortElements($a, $b)
    {
        if($a->order == $b->order) {
            return 0;
        }
        
        return ($a->order < $b->order) ? -1 : 1;
    }
    
    private function _loadElements()
    {
        $this->_elements = $this->getTable('Element')->findBy(array('element_set_id'=>$this->id));
    }
    

}
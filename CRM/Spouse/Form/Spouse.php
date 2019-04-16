<?php

use CRM_Spouse_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Spouse_Form_Spouse extends CRM_Core_Form {
  public function buildQuickForm() {

    // add form elements
    $this->add(
      'select', // field type
      'partner_relationship_type_id', // field name
      'Spouse/Partner relationship', // field label 
      $this->getRelationshipTypeOptions(), // list of options
      TRUE // is required
    );
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    $this->setDefaults(
      array(
        'partner_relationship_type_id' => spouse_get_partner_relationship_type_id()
      )
    );

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    $id = $values['partner_relationship_type_id'];
    $options = $this->getRelationshipTypeOptions();
    CRM_Core_Session::setStatus(E::ts('Saved "%1"', array(
      1 => $options[$id],
    )));

    $ret = civicrm_api3('Setting', 'create', array('partner_relationship_type_id' => $id));
    parent::postProcess();
  }

  public function getRelationshipTypeOptions() {
    $ret = array();
    $results = civicrm_api("RelationshipType","get", array ('version' =>'3', 'contact_type_a' =>'Individual', 'contact_type_b' =>'Individual'));
    foreach($results['values'] as $id => $value) {
      if(empty($value['description'])) {
        $display = $value['label_a_b'];
      } else {
        $display = $value['description'];
      }
      $ret[$id] = $display;
    }
    return $ret;
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}

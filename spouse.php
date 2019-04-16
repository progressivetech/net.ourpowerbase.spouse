<?php

require_once 'spouse.civix.php';
use CRM_Spouse_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function spouse_civicrm_config(&$config) {
  _spouse_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function spouse_civicrm_xmlMenu(&$files) {
  _spouse_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function spouse_civicrm_install() {
  _spouse_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function spouse_civicrm_postInstall() {
  _spouse_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function spouse_civicrm_uninstall() {
  _spouse_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function spouse_civicrm_enable() {
  _spouse_civix_civicrm_enable();

  // CiviCRM ships with both a partner and a spouse relationship type. This
  // is confusing. When enabled, we will try to disable the partner onee,
  // but only if we can get away with it (i.e. if it is not in use).
  try {
    $partner_relationship = civicrm_api3('RelationshipType', 'getsingle', [
      'name_a_b' => "Partner is",
      'is_active' => 1,
    ]);

    $partner_relationship_type_id = $partner_relationship['id'];

    // Make sure this is not the default value.
    $default_partner_relationship_type_id = spouse_get_default_partner_relationship_type_id();

    if ($partner_relationship_type_id == $default_partner_relationship_type_id) {
      // Woops, don't disable the default one.
      return;
    }

    // Lastly, ensure it is not in use.
    $sql = "SELECT COUNT(id) AS count FROM civicrm_relationship WHERE relationship_type_id = %0";
    $dao = CRM_Core_DAO::executeQuery($sql, array(0 => array($partner_relationship_type_id, 'Integer')));
    $dao->fetch();
    if ($dao->count > 0) {
      // Don't disable if in use.
      return;
    }

    // Hooray, we can disable.
    $partner_relationship['is_active'] = 0;
    civicrm_api3('RelationshipType', 'create', $partner_relationship);
  }
  catch (CiviCRM_API3_Exception $e) {
    // We get an error if it doesn't exist. Oh well, we tried.
  }
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function spouse_civicrm_disable() {
  _spouse_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function spouse_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _spouse_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function spouse_civicrm_managed(&$entities) {
  _spouse_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function spouse_civicrm_caseTypes(&$caseTypes) {
  _spouse_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function spouse_civicrm_angularModules(&$angularModules) {
  _spouse_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function spouse_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _spouse_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function spouse_civicrm_entityTypes(&$entityTypes) {
  // _spouse_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function spouse_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function spouse_civicrm_navigationMenu(&$menu) {
  _spouse_civix_insert_navigation_menu($menu, 'Administer/Customize Data and Screens', array(
    'label' => E::ts('Spouse Settings'),
    'name' => 'spouse_settings',
    'url' => 'civicrm/admin/spouse',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _spouse_civix_navigationMenu($menu);
} 



function spouse_civicrm_tokens(&$tokens) {
  $id = variable_get('opb_partner_relationship_id', opb_get_default_partner_relationship_id());
  // Don't offer partner token if the relationship has not been specified.
  if($id) {
    $tokens['partner'] = array(
      'partner.full_name' => 'Partner/Spouse Full Name',
      'partner.first_name' => 'Partner/Spouse First Name',
      'partner.last_name' => 'Partner/Spouse Last Name',
      'partner.street_address' => 'Partner/Spouse Address',
      'partner.city' => 'Partner/Spouse City',
      'partner.state' => 'Partner/Spouse State',
      'partner.zip' => 'Partner/Spouse Zipcode',
      'partner.contact_and_partner_first_name' => 'Contact and Partner/Spouse first name',
      'partner.contact_and_partner_full_name' => 'Contact and Partner/Spouse full name',
    );
  }
}

function spouse_civicrm_tokenValues( &$values, $contactIDs, $job = null, $tokens = array(), $context = null ) {
  # $tokens and $context do not (yet) seem to be implemented. grep -r the code for CRM_Utils_Hook::tokenValues to
  # see when it has been implemented - because then we can get a performance boost by not doing all these
  # sql queries every time this function is fired.
  spouse_process_relationship_tokens($values, $contactIDs, $job, $tokens, $context);
}

function spouse_process_relationship_tokens(&$values, $contactIDs, $job, $tokens, $context) {
  // Save on CPU - return if none of our tokens are in there.
  if (!array_key_exists('partner', $tokens)) {
    return;
  }

  // Get the default partner relationship type id.
  $id = spouse_get_partner_relationship_type_id();

  // If it's empty, nothing to do.
  if(empty($id)) {
    return;
  }

  $contactIDString = implode(',', array_values($contactIDs));
  // Complex SQL query to find all possible partners. We have to check
  // both contact_id_a and contact_id_b. In the result,
  // we should have search_contact_id (which is the contact id we are searching on)
  // and partner_id which is a corresponding partner_id.
  $sql = "( SELECT DISTINCT c.id AS partner_id, r.contact_id_b as search_contact_id, ".
    "display_name, first_name, last_name, street_address, city, state_province_id, postal_code " .
    "FROM civicrm_contact c JOIN ".
    "civicrm_relationship r ON c.id = r.contact_id_a LEFT JOIN ".
    "civicrm_address a ON c.id = a.contact_id ".
    "WHERE r.contact_id_b in ( $contactIDString ) ".
    "AND r.relationship_type_id = %1 AND c.is_deleted = 0 ".
    "AND r.is_active = 1 AND (a.is_primary = 1 OR a.is_primary IS NULL)) ".
    "UNION DISTINCT ".
    "( SELECT DISTINCT c.id AS partner_id, r.contact_id_a as search_contact_id, ".
    "display_name, first_name, last_name, street_address, city, state_province_id, postal_code " .
    "FROM civicrm_contact c JOIN ".
    "civicrm_relationship r ON c.id = r.contact_id_b LEFT JOIN ".
    "civicrm_address a ON c.id = a.contact_id ".
    "WHERE r.contact_id_a in ( $contactIDString ) ".
    "AND r.relationship_type_id = %1 AND c.is_deleted = 0 ".
    "AND r.is_active = 1 AND (a.is_primary = 1 OR a.is_primary IS NULL)) ";

  $params[1] = array(
    $id,
    'Integer',
  );
  $dao = CRM_Core_DAO::executeQuery( $sql, $params );
  // If someone has more than one partner, we shamefully restrict their
  // polyamorous perogatives by only substituting the first relationship.
  $seen = array();
  while ( $dao->fetch( ) ) {
    if(in_array($dao->search_contact_id,$seen)) continue;
    $id = $dao->search_contact_id;
    if(!array_key_exists($id,$values)) {
      $values[$id] = array();
    }
    $values[$id]['partner.full_name'] = $dao->display_name;
    $values[$id]['partner.first_name'] = $dao->first_name;
    $values[$id]['partner.last_name'] = $dao->last_name;
    $values[$id]['partner.street_address'] = $dao->street_address;
    $values[$id]['partner.city'] = $dao->city;
    $values[$id]['partner.state'] = CRM_Core_PseudoConstant::stateProvinceAbbreviation($dao->state_province_id);
    $values[$id]['partner.zip'] = $dao->postal_code;

     // Don't repeat if someone has more than one relationship.
    $seen[] = $id;
  }

  // We have to look up the first name and display name for the
  // search contact in order to populate the contact AND partner fields, which
  // should populate with just the contact info if there is no relationship OR
  // the contact info AND the partner info if the partner relationship is there.
  reset($contactIDs);
  while(list(, $contactID) = each($contactIDs)) {
    // First lookup the contacts first name and full name because we will always use
    // that information.
    $params = array('id' => $contactID, 'return' => array('first_name', 'display_name'));
    $result = civicrm_api3('contact', 'getsingle', $params);

    // Initialize the array if necessary (the partner search might not have
    // returned any results).
    if(!array_key_exists($contactID, $values)) {
      $values[$contactID] = array(
        'partner.contact_and_partner_first_name' => NULL,
        'partner.contact_and_partner_full_name' => NULL
      );
    }
    // More initializing in case no partner is present...
    if(!array_key_exists('partner.first_name', $values[$contactID])) {
      $values[$contactID]['partner.first_name'] = NULL;
    }
    if(!array_key_exists('partner.full_name', $values[$contactID])) {
      $values[$contactID]['partner.full_name'] = NULL;
    }

    // Now, add the contact first name and full name - which should be
    // present in all cases.
    $values[$contactID]['partner.contact_and_partner_first_name'] = $result['first_name'];
    $values[$contactID]['partner.contact_and_partner_full_name'] = $result['display_name'];

    // Check to see if the partner info was populuted. If so, append it.
    if(!empty($values[$contactID]['partner.first_name'])) {
      $values[$contactID]['partner.contact_and_partner_first_name'] .= ' and ' .
        $values[$contactID]['partner.first_name'];
    }
    // Check for partner full name
    if(!empty($values[$contactID]['partner.full_name'])) {
      $values[$contactID]['partner.contact_and_partner_full_name'] .= ' and ' .
        $values[$contactID]['partner.full_name'];
    }
  }
}

function spouse_get_partner_relationship_type_id() {
    $ret = civicrm_api3('Setting', 'getvalue', array('name' => 'partner_relationship_type_id'));
    if ($ret) {
      return $ret;
    }

    // Nothing set, get default
    $ret = spouse_get_default_partner_relationship_type_id();
    if ($ret) {
      // Save this setting.
      $ret = civicrm_api3('Setting', 'create', array('partner_relationship_type_id' => $ret));
      return $ret;
    }

    // Nothing set, no reasonable default.
    return NULL;
}

function spouse_get_default_partner_relationship_type_id() {
  // For backward compatability with PTP sites (prior to 2018), check to see if a setting exists.
  if (function_exists('variable_get')) {
    $id = variable_get('opb_partner_relationship_id', NULL);
    if ($id) {
      return $id;
    }
  }

  try {
    $result = civicrm_api3('RelationshipType', 'getsingle', [
      'name_a_b' => "Spouse is",
    ]);
    return $result['id'];
  }
  catch (CiviCRM_API3_Exception $e) {
    // We will get an error if it doesn't exist. Just return null;
    return null;
  }

}

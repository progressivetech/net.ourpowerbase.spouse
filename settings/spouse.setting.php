<?php

/**
 * Settings used by sumfields.
 */

return array(
  'partner_relationship_type_id' => array(
    'group_name' => 'Spouse Setting',
    'group' => 'spouse',
    'name' => 'partner_relationship_type_id',
    'type' => 'Integer',
    'default' => 0,
    'add' => '5.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Used as relationship_type_id to identify the correct values when generating tokens.',
    'help_text' => 'Choose the relationship type to use as your default spouse/partner relationship when generating tokens',
	),
);

{* HEADER *}

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="top"}
</div>

<div class="description">The spouse extension will automatically provide tokens that will be populated with values such as First Name and Partner First Name. To provide these tokens, we have to know which relationship defines your partner/spouse relationship. Please choose that relationship below.</div>

  <div>
    <span>{$form.partner_relationship_type_id.label}</span>
    <span>{$form.partner_relationship_type_id.html}</span>
  </div>

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

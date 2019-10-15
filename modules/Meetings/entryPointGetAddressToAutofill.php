<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

// Get variables from request
$module = $_REQUEST['module'];
$record_id = $_REQUEST['record_id'];

// Retrieve bean
$bean = BeanFactory::getBean($module, $record_id);

// On any retrieval failure, return 'No Data' JSON
if(empty($bean)){
    die('{"No Data":""}');
}

// Split out billing address keys into seperate array
$billing_address_array = array();
foreach ($bean as $key => $value) {
    if (strpos($key, 'billing_address_') === 0) {
        $billing_address_array[$key] = $value;
    }
}

// Split out shipping address keys into seperate array
$shipping_address_array = array();
foreach ($bean as $key => $value) {
    if (strpos($key, 'shipping_address_') === 0) {
        $shipping_address_array[$key] = $value;
    }
}

// Create billing and shipping address strings from arrays
$billing_address_string = trim(preg_replace('/\s+/', ' ', implode(" ", $billing_address_array)));
$shipping_address_string = trim(preg_replace('/\s+/', ' ', implode(" ", $shipping_address_array)));

// Populate new array with translated labels (minus any trailing :) and address strings
$output_array = array();
$output_array[rtrim(translate('LBL_BILLING_ADDRESS'), ':')] = $billing_address_string;
$output_array[rtrim(translate('LBL_SHIPPING_ADDRESS'), ':')] = $shipping_address_string;

// Output the array as JSON
echo json_encode($output_array);
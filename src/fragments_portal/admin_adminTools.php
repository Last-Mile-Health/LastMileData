<script>
<?php

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    
    // Echo JSON (sidebar model)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/json_objects/3";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);
    
    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var admin_tools_config = JSON.parse($json1.object_data);";
    
?>

// Load main script
$.getScript('../js/admin_adminTools.js');
</script>

<h2>Admin tools</h2>
<hr>

<div id="outerDiv">
    
    <table id="adminTools_table" class="table table-striped">
        <tr>
            <th>Tool</th>
            <th>Description</th>
            <th>Status</th>
            <th>Toggle</th>
        </tr>
        <tr>
            <td>Data peek</td>
            <td>When enabled, superadmins can view the previous month's data one week early (i.e. on the 8th of the month instead of the 15th)</td>
            <td>
                <span data-bind="if:config.peek">Enabled</span>
                <span data-bind="ifnot:config.peek">Disabled</span>
            </td>
            <td><button data-bind="click:actions.peek" class="repButton btn btn-s btn-primary">Toggle</button></td>
        </tr>
        <tr>
            <td>Suppress data</td>
            <td>When enabled, data from the previous month is suppressed for all users for up to one week (i.e. until the 22nd of the month). Peek overrides this for superadmins.</td>
            <td>
                <span data-bind="if:config.suppress">Enabled</span>
                <span data-bind="ifnot:config.suppress">Disabled</span>
            </td>
            <td><button data-bind="click:actions.suppress" class="repButton btn btn-s btn-primary">Toggle</button></td>
        </tr>
        <tr>
            <td>Down for maintenance</td>
            <td>When enabled, this displays a "down for maintenance" message instead of displaying Data Portal content.</td>
            <td>
                <span data-bind="if:config.maintenance">Enabled</span>
                <span data-bind="ifnot:config.maintenance">Disabled</span>
            </td>
            <td><button data-bind="click:actions.maintenance" class="repButton btn btn-s btn-primary">Toggle</button></td>
        </tr>
    </table>
    
    <button id="saveButton" data-bind="click:actions.saveChanges" class="btn btn-success" style="width:250px">Save configuration changes</button>
    
</div>
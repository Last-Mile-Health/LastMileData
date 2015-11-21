<link rel="stylesheet" href="../css/admin_editMarkdown.css" type="text/css" />

<script>
<?php
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (indicator METADATA)
    $url = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/markdown/";
    curl_setopt($ch,CURLOPT_URL,$url);
    $json = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var markdownList = $json;". "\n\n";
?>

// Load main script
$.getScript('../js/admin_editMarkdown.js');

</script>

<div id="outerDiv">

    <h2>Edit markdown</h2>

    <div class="tableContainer">
        <table>
            <thead id="adminHeader">
                <tr>
                    <th class="pad darkBlue">Name</th>
                    <th class="pad darkBlue">Markdown text</th>
                    <th class="pad darkBlue">&nbsp;X&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody id="scrollContent" data-bind="foreach:vmData">
                <tr class="filterRow">
                    <td><input class="admin_input pad" data-bind="value: mdName, event: {change:$root.other.actions.change, blur:$root.other.actions.blur}"></td>
                    <td><textarea accept-charset="ISO-8859-1" class="admin_input pad filterCut" data-bind="value: mdText, event: {change:$root.other.actions.change}"></textarea></td>
                    <td><button data-bind="click:$root.other.actions.delete, attr:{'data-cid':_cid}" class="btn btn-xs btn-danger btn_remove">X</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:5px; font-size:150%">
        <button id="btn_add" class="btn btn-primary">Add a new markdown file</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
    </div>
    
</div>

<?php

for($i=1;$i<=15;$i++) {
Echo "                        <!-- Row $i of 15 -->\n";
Echo "                        <tr>\n";
Echo "                            <td>$i</td>\n";
Echo "                            <td><input class='stored c2' id='firstName_$i'></td>\n";
Echo "                            <td><input class='stored c3' id='lastName_$i'></td>\n";
Echo "                            <td><input class='stored c4' id='staffID_$i'></td>\n";
Echo "                            <td>\n";
Echo "                                <input type='checkbox' class='stored' id='sex_M_$i'>M&nbsp;\n";
Echo "                                <input type='checkbox' class='stored' id='sex_F_$i'>F&nbsp;\n";
Echo "                            </td>\n";
Echo "                            <td><input class='stored c6' id='organization_$i' data-lmd-valid-select='[" . '"Last Mile Health","County Health Team","Partners In Health"' . "]'></td>\n";
Echo "                            <td><input class='stored c7' id='location_$i' data-lmd-valid-select='[" . '"Grand Gedeh","Rivercess","Monrovia"' . "]'></td>\n";
Echo "                            <td><input class='stored c8' id='role_$i' data-lmd-valid-select='[" . '"Frontline Health Worker","FHW Leader","Clinical Mentor","OIC","Physician Assistant","Nurse Aide","Nurse","Lab Technician"' . "]'></td>\n";
Echo "                        </tr>\n\n";
    
}
?>
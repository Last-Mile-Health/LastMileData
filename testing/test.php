<?php


for ($i=1;$i<=15;$i++) {

echo "                        <!-- Row $i of 15 -->" . "\n";
echo "                        <tr>" . "\n";
echo "                            <td>$i</td>" . "\n";
echo "                            <td><input class='stored c2' id='firstName_$i'></td>" . "\n";
echo "                            <td><input class='stored c3' id='lastName_$i'></td>" . "\n";
echo "                            <td><input class='stored c4' id='staffID_$i'></td>" . "\n";
echo "                            <td>" . "\n";
echo "                                <input type='checkbox' class='stored' id='sex_M_$i'>M&nbsp;" . "\n";
echo "                                <input type='checkbox' class='stored' id='sex_F_$i'>F&nbsp;" . "\n";
echo "                            </td>" . "\n";
echo "                            <td><input class='stored c6' id='organization_$i'></td>" . "\n";
echo "                            <td><input class='stored c7 datepicker' id='location_$i'></td>" . "\n";
echo "                            <td><input class='stored c8 datepicker' id='role_$i'></td>" . "\n";
echo "                        </tr>" . "\n\n";
    
}

 ?>
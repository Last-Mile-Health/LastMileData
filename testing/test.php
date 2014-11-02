<?php


for ($i=1;$i<=25;$i++) {

echo "                    <!-- Row $i of 25 -->" . "\n";
echo "                    <tr>" . "\n";
echo "                        <td class='c1'>$i</td>" . "\n";
echo "                        <td><input class='stored c2 integer' id='hhID_$i'></td>" . "\n";
echo "                        <td><input class='stored c3' id='memID_$i' ></td>" . "\n";
echo "                        <td><input class='stored c4' id='firstName_$i'></td>" . "\n";
echo "                        <td><input class='stored c4' id='lastName_$i'></td>" . "\n";
echo "                        <td class='c5-8'><input type='checkbox' class='stored' id='birth_$i'></td>" . "\n";
echo "                        <td class='c5-8'><input type='checkbox' class='stored' id='death_$i'></td>" . "\n";
echo "                        <td class='c5-8'><input type='checkbox' class='stored' id='movedIn_$i'></td>" . "\n";
echo "                        <td class='c5-8'><input type='checkbox' class='stored' id='movedOut_$i'></td>" . "\n";
echo "                        <td><input class='stored c9 datepicker' id='bdmDate_$i'></td>" . "\n";
echo "                        <td class='c10'>" . "\n";
echo "                            <input type='checkbox' class='stored' id='sex_M_$i'>M&nbsp;" . "\n";
echo "                            <input type='checkbox' class='stored' id='sex_F_$i'>F&nbsp;" . "\n";
echo "                        </td>" . "\n";
echo "                        <td><input class='stored c11 integer' id='ageOrYOB_$i'></td>" . "\n";
echo "                    </tr>" . "\n\n";
    
}

 ?>
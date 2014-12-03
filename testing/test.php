<?php

for($i=1;$i<=23;$i++) {
Echo "                    <tr>\n";
Echo "                        <td><input id='memberName_$i' class='stored c1'></td>\n";
Echo "                        <td><input disabled class='c2'></td>\n";
Echo "                        <td><input disabled class='c3'></td>\n";
Echo "                        <td><input disabled class='c4'></td>\n";
Echo "                        <td><input id='memberID_$i' class='stored c5'></td>\n";
Echo "                        <td><input id='visitDate_$i' class='stored datepicker c6'></td>\n";
Echo "                        <td><input id='screedingCodes_$i' class='stored c7' data-lmd-valid-regex='^([A-E]{0,5})$' data-lmd-valid-errormessage='must consist of characters A,B,C,D,E (no spaces, commas, etc)'></td>\n";
Echo "                        <td><input id='educationCodes_$i' class='stored c8' data-lmd-valid-regex='^([A-E]{0,5})$' data-lmd-valid-errormessage='must consist of characters A,B,C,D,E (no spaces, commas, etc)'></td>\n";
Echo "                    </tr>\n";
    
}
?>
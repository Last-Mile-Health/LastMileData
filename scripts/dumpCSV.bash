#!/bin/bash

user=lastmile_admin
password=LastMile14

csvFileDirectory=/home/lastmilehealth/public_html/LastMileData/backups/CSVs

# sed commands to convert SQL statement output to csv format.
replaceTab=\ sed\ 's/\t/\",\"/g'\ 
replaceBeginOfLine=sed\ 's/^/\"/g' 
replaceEndOfLine=sed\ 's/$/\"/g'
replaceNULL=sed\ 's/\"NULL\"/\"\"/g'

dateStamp=`date +"%Y-%m-%d"`

query1='select * from lastmile_chwdb.view_msr'
fileName1=view_msr

query2='select * from lastmile_chwdb.view_odk_scf_nick'
fileName2=view_odk_scf_nick

query3='select * from lastmile_cha.temp_view_chss_cha_report'
fileName3=temp_view_chss_cha_report

mysql --batch --user=$user --password=$password -e "${query1}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName1}_$dateStamp.csv 

mysql --batch --user=$user --password=$password -e "${query2}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName2}_$dateStamp.csv 

mysql --batch --user=$user --password=$password -e "${query3}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName3}_$dateStamp.csv 

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

query='select * from lastmile_chwdb.view_msr'
fileName=view_msr

mysql --batch --user=$user --password=$password -e "${query}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName}_$dateStamp.csv 

#mysql --user=$user --password=$password -e "${query}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName}_$dateStamp.csv 

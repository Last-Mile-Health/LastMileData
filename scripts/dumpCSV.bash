#!/bin/bash

user=lastmile_admin
password=LastMile14

csvFileDirectory=/home/lastmilehealth/public_html/LastMileData/backups/CSVs

# sed commands to convert SQL output to CSV file format.
replaceTab=\ sed\ 's/\t/\",\"/g'\ 
replaceBeginOfLine=sed\ 's/^/\"/g' 
replaceEndOfLine=sed\ 's/$/\"/g'
replaceNULL=sed\ 's/\"NULL\"/\"\"/g'

dateStamp=`date +"%Y-%m-%d"`

query1='select * from lastmile_cha.view_base_cha'
query2='select * from lastmile_cha.view_base_chss'
query3='select * from lastmile_cha.view_base_geo_community'
query4='select * from lastmile_cha.view_base_geo_community_in_program'
query5='select * from lastmile_cha.view_base_geo_community_remote'
query6='select * from lastmile_cha.view_base_history_person'
query7='select * from lastmile_cha.view_base_history_person_position'
query8='select * from lastmile_cha.view_base_position'
query9='select * from lastmile_cha.view_base_position_cha'
query10='select * from lastmile_cha.view_base_position_chss'
query11='select * from lastmile_program.view_train_cha'

fileName1=view_base_cha
fileName2=view_base_chss
fileName3=view_base_geo_community
fileName4=view_base_geo_community_in_program
fileName5=view_base_geo_community_remote
fileName6=view_base_history_person
fileName7=view_base_history_person_position
fileName8=view_base_position
fileName9=view_base_position_cha
fileName10=view_base_position_chss
fileName11=view_train_cha

mysql --batch --user=$user --password=$password -e "${query1}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName1}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query2}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName2}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query3}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName3}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query4}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName4}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query5}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName5}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query6}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName6}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query7}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName7}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query8}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName8}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query9}"  | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName9}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query10}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName10}_$dateStamp.csv 
mysql --batch --user=$user --password=$password -e "${query11}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName11}_$dateStamp.csv 

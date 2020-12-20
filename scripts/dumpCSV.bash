#!/bin/bash

user=lastmile_admin
password=LastMile14

# LMD MERL downloads
csvFileDirectory=/home/lastmilehealth/public_html/LastMileData/backups/CSVs

# Delete yesterday's csv files
rm -f ${csvFileDirectory}/*.csv

# sed commands to convert SQL output to CSV file format.
replaceTab=\ sed\ 's/\t/\",\"/g'\ 
replaceBeginOfLine=sed\ 's/^/\"/g' 
replaceEndOfLine=sed\ 's/$/\"/g'
replaceNULL=sed\ 's/\"NULL\"/\"\"/g'

dateStamp=`date +"%Y-%m-%d"`

# View queries 
query1='select * from lastmile_ncha.view_base_cha'
query2='select * from lastmile_ncha.view_base_chss'
query3='select * from lastmile_ncha.view_base_geo_community'
query4='select * from lastmile_ncha.view_base_geo_community_in_program'
query5='select * from lastmile_ncha.view_base_geo_community_remote'
query6='select * from lastmile_ncha.view_base_history_person'
query7='select * from lastmile_ncha.view_base_history_person_position'
query8='select * from lastmile_ncha.view_base_position'
query9='select * from lastmile_ncha.view_base_position_cha'
query10='select * from lastmile_ncha.view_base_position_chss'
query11='select * from lastmile_report.view_base_msr'
query12='select * from lastmile_report.view_history_person_position_cha_train'
query13='select * from lastmile_report.view_base_position_person_cha_chss_qao'
query14='select * from lastmile_report.view_restock_level'
query15='select * from lastmile_report.view_restock_cha_ppe_form'

# Table queries 
query100='select * from lastmile_upload.de_case_scenario'
query101='select * from lastmile_upload.de_case_scenario_2'
query102='select * from lastmile_upload.odk_sickChildForm'
query103='select * from lastmile_upload.odk_chaRestock'
query104='select * from lastmile_upload.odk_routineVisit'
query105='select * from lastmile_upload.odk_supervisionVisitLog'
query106='select * from lastmile_upload.odk_vaccineTracker'
query107='select * from lastmile_upload.odk_QAOSupervisionChecklistForm'
query108='select * from lastmile_upload.de_chaHouseholdRegistration'
query109='select * from lastmile_upload.de_cha_monthly_service_report'
query110='select * from lastmile_upload.de_chss_monthly_service_report'
query111='select * from lastmile_upload.de_cha_status_change_form'
query112='select * from lastmile_upload.de_chss_commodity_distribution'
query113='select * from lastmile_program.train_cha'
query114='select * from lastmile_program.train_chss'
query115='select * from lastmile_upload.odk_QCA_GPSForm'

# View file names
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
fileName11=view_base_msr
fileName12=view_history_person_position_cha_train
fileName13=master_list_qao_chss_cha_position_person_community
fileName14=view_restock_level
fileName15=view_restock_cha_ppe_form


# Table file names
fileName100=de_case_scenario
fileName101=de_case_scenario_2
fileName102=odk_sickChildForm
fileName103=odk_chaRestock
fileName104=odk_routineVisit
fileName105=odk_supervisionVisitLog
fileName106=odk_vaccineTracker
fileName107=odk_QAOSupervisionChecklistForm
fileName108=de_chaHouseholdRegistration
fileName109=de_cha_monthly_service_report
fileName110=de_chss_commodity_distribution
fileName111=de_cha_status_change_form
fileName112=de_chss_commodity_distribution
fileName113=train_cha
fileName114=train_chss
fileName115=odk_QCA_GPSForm



# Views go here...
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
mysql --batch --user=$user --password=$password -e "${query12}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName12}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query13}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName13}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query14}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName14}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query15}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName15}_$dateStamp.csv

# Tables go here...
mysql --batch --user=$user --password=$password -e "${query100}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName100}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query101}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName101}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query102}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName102}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query103}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName103}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query104}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName104}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query105}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName105}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query106}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName106}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query107}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName107}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query108}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName108}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query109}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName109}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query110}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName110}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query111}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName111}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query112}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName112}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query113}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName113}_$dateStamp.csv
mysql --batch --user=$user --password=$password -e "${query114}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName114}_$dateStamp.csv

mysql --batch --user=$user --password=$password -e "${query115}" | $replaceTab | $replaceBeginOfLine | $replaceEndOfLine | $replaceNULL >  ${csvFileDirectory}/${fileName115}_$dateStamp.csv


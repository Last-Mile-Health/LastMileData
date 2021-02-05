#!/bin/bash

echo "----------------------------------------------------------------------------------------------------------------"
echo `date '+%B %d, %Y'`
echo "----------------------------------------------------------------------------------------------------------------"

user=
password=

# email_recipient=oeddins@lastmilehealth.org
email_recipient=LMD@lastmilehealth.org

csv_file_directory=/home/lastmilehealth/public_html/LastMileData/backups/cha_status_change_request/
file_name=cha_status_change_request

# sed commands to convert SQL output to CSV file format.
replace_tab=\ sed\ 's/\t/\",\"/g'\
replace_begin_of_line=sed\ 's/^/\"/g'
replace_end_of_line=sed\ 's/$/\"/g'
replace_null=sed\ 's/\"NULL\"/\"\"/g'

date_time_stamp=`date +"%Y_%m_%d_%H_%M_%S"`
date_formatted=`date '+%B %d, %Y'`

# This bash script is called at 3 a.m. in the morning.  Query yesterday's employment status change request.
sql_query='select * from lastmile_upload.view_cha_status_change_request'

if [ -f ${csv_file_directory}${file_name}.csv ]
then
        rm -f ${csv_file_directory}${file_name}.csv
fi

mysql --batch --user=$user --password=$password -e "${sql_query}"  | $replace_tab | $replace_begin_of_line | $replace_end_of_line | $replace_null >  ${csv_file_directory}${file_name}.csv

number_line=`cat ${csv_file_directory}${file_name}.csv | wc -l`

if [ ${number_line} -gt 0 ]
then
        echo "Number records: `expr ${number_line} - 1`"

 # mailx -a ${csv_file_directory}${file_name}.csv -s "Nightly Automated CHA Status Change Request: ${date_formatted}" ${email_recipient} < /dev/null

# This embeds the csv file in the body of the email.  You could use awk, sed, and other text parsing Linux tools to format the email body to be more human readable.
# cat ${csv_file_directory}${file_name}.csv | mailx -a ${csv_file_directory}${file_name}.csv -s "Nightly Automated CHA Status Change Request: ${date_formatted}; `expr ${number_line} - 1` record(s) entered" ${email_recipient}

        mailx -a ${csv_file_directory}${file_name}.csv -s "Nightly Automated CHA Status Change Request: ${date_formatted}; `expr ${number_line} - 1` record(s) entered" ${email_recipient} < /dev/null

        mv ${csv_file_directory}${file_name}.csv ${csv_file_directory}archive/${file_name}_${date_time_stamp}.csv
else
        echo "Number records: ${number_line}"

        mailx -s "Nightly Automated CHA Status Change Request: ${date_formatted}; ${number_line} records entered" ${email_recipient}  < /dev/null

        rm -f ${csv_file_directory}${file_name}.csv
fi

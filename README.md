Last Mile Data - README
=======================

* AUTHORS:        Avi Kenny, Owen Eddins
* LAST UPDATED:   2017-11-25
* COPYRIGHT:      Copyright 2017 Last Mile Health
* LICENSE:        Distributed under the terms of the GNU General Public License


I. Overview
-----------

*Last Mile Data* is a web application used for all programmatic data collection and reporting within [Last Mile Health](www.lastmilehealth.org). It is currently hosted at [LastMileData.org](https://www.lastmiledata.org).

Most internal staff primarily use the *Data Portal* page, which provides a number of data reports on supervision, supply chain, CHW services, and programmatic scale. A typical report contains a logically-grouped set of indicators, each of which is presented together with the indicator's metadata (definition + data source), a table showing data from the last few months, a visualization of historical trends, and buttons to download the raw data.

![Last Mile Data](https://www.lastmiledata.org/LastMileData/build/images/LMD_screenshot_1.jpg)

A *Data Portal* feature that we added recently allows users to dynamically plot data onto a map of Liberia at the county, district, or community level. This helps program managers to assess geospatial trends that might otherwise be difficult to notice.

![Last Mile Data](https://www.lastmiledata.org/LastMileData/build/images/LMD_screenshot_2.jpg)

Many reports are created with our field staff in mind. These reports typically disaggregate data at the level of the individual community health worker or supervisor, in order to enable data-driven performance management.

![Last Mile Data](https://www.lastmiledata.org/LastMileData/build/images/LMD_screenshot_3.jpg)

Staff on our Research, Monitoring, & Evaluation (RM&E) team often interact with the *Data Entry / Quality Assurance* page of *Last Mile Data*. Here, paper forms are entered and quality-checked, and mobile health data is uploaded. This section can be accessed offline when no internet connection is present.

![Last Mile Data](https://www.lastmiledata.org/LastMileData/build/images/LMD_screenshot_4.jpg)

We're in the process of turning *Last Mile Data* into a comprehensive business intelligence platform that can be managed and configured by non-technical staff. For example, the "Edit Reports" tool (shown below) allows non-technical staff to create and edit data reports that show up in the *Data Portal*.

![Last Mile Data](https://www.lastmiledata.org/LastMileData/build/images/LMD_screenshot_5.jpg)


II. Data Portal functionality
-----------------------------

#### Overview
Text

#### Organization
The data portal 


III. Data Entry / Quality Assurance functionality
-------------------------------------------------

#### Overview

The purpose of this part of the application is to allow for data entry of data from paper forms into corresponding (ideally identical) HTML web forms, and allow for mHealth data (from ODK-Liberia) to be uploaded. Given the poor internet connectivity in rural Liberia, the system was created such that both the data entry and data quality assurance processes can occur offline. Data entry is the process of taking a paper form, copying the information onto the HTML form, and submitting the form. In the quality assurance process, a user (ideally a different user than the one who performed data entry) pulls up a record based on a set of identifying variables (similar to a primary key) and visually compares the paper form with the HTML form to check for and correct any data entry errors. After quality assurance, the records can be sent to the MySQL database for reporting and permanent storage (if an internet connection is present).

#### *Data entry* workflow

1. A user (typically a Data Clerk or an M&E Officer) navigates to the *DE/QA* page (`page_deqa.html`), and logs in.
2. The user clicks on one of the green buttons to perform data entry (e.g. *TEST FORM*, `0_testDE.html`).
3. The user fills out the form fields and clicks submit.
4. When the submit button is clicked, a script runs within `fhwForms.js`, that performs form validation and then stores the form locally.
5. In the form validation step, the application loops through all fields with `class='stored'` and runs it through a series of tests, some of which correspond to the HTML attributes of that field. For example, if the field has !!!!! check this !!!!! `class='integer'`, then this script checks if it is an integer. If at least one field fails the data validation step, a series of error messages are displayed to the user at the top of the screen.
6. If the form clears the validation step, to proceeds to the "data storage" step. Here, the application loops through all fields with `class='stored'` and stores it as a key-value pair within the 'myRecord' object. The key is the field id attribute (NOT the name attribute), and the value is the user-entered field value.
7. The Filesystem API is used to read in a single file (`data.lmd`, a serialized JSON object) and parse it into the 'myRecordset' object. If the file is blank, 'myRecordset' is set to an empty object. The entire 'myRecord' object is serialized and stored as a value within `myRecordset` (the key is an arbitrary numeric key).
8. The `myRecordset` object is serialized and written to the data.lmd file (the entire file is overwritten).
9. The user is redirected to the "DEQA" page.


#### The quality assurance workflow happens as such:

1. A user (the supervisor) navigates to the "DEQA" page on the data entry clerk's laptop (assuming the data entry clerk already logged in).
2. The user clicks on one of the blue buttons to perform quality assurance (e.g. "TEST FORM", `0_testDE.html`).
3. A modal form pops up with 1-3 fields. These fields represent the "primary key" of the form in question. The primary key must uniquely identify a single paper form; for example, for a "sick child form", the (composite) primary key is composed of the 'memberID' and 'visitDate' fields for a "sick child form" (where 'memberID' is the unique identifier of a single person within the system, and we assume that a single person can have no more than one visit per day). The user looks at the paper form, enters the field values, and clicks submit (`id='qaModalSubmit'`).
4. A script runs within deqa.js that checks for a "match" within the `myRecordset` object. If no message is found, an error message is displayed to the user. If a match is found, the user is redirected to the data entry form (a query parameter is passed, with key "QA" and a value equivalent to the key in the `myRecordset` object that identifies the record).
5. The data entry form detects that the QA parameter is not undefined, the data.lmd is read and parsed into the 'myRecordset' object. The matching record within the myRecordset object is found, and the value is parsed into the 'currentRecord' object. The script loops through the fields of the `currentRecord` object, and for each key/value pair in the 'currentRecord' object, the HTML form field with the id attribute equal to the 'currentRecord' key is set to the 'currentRecord' value.
6. If any changes are made to the field values, the user may click submit, and the record is processed just as in the data entry workflow. The only difference is that before adding the field to the 'myRecordset' object, the "old" key/value pair within the myRecordset object is deleted.


#### If the user has an internet connection and wants to send records to the MySQL database:

1. A user (the supervisor) navigates to the "DEQA" page on the data entry clerk's laptop (assuming the data entry clerk already logged in) and clicks the red "Send Records" button.
2. A modal pops up in which the user is prompted to confirm the button click. If the user clicks the "Yes, send records" button (`id='sendRecords'`), a script within `deqa.js` runs.
3. The `data.lmd` file is read and parsed into the `myRecordset` object. The key/value pairs within `myRecordset` are looped through. Within this loop, the value (which represents one stored form) is parsed into the `currentRecord` object, which is then parsed into a SQL INSERT query, and an AJAX request is created (but not yet sent) which contains both the query string and the key of `myRecordset` that uniquely identifies the record.
4. The entire batch of AJAX request objects is now stored within the `ajaxRequests` array. The requests are sent to `ajaxSendQuery.php`, which sends the records to the MySQL database.
5. As each AJAX request resolves, it's success or error handler is triggered. The success handler deletes the corrseponding record from the `myRecordset` object; the error handler does not (i.e. the record is only removed from `myRecordset` if it was successfully inserted into the MySQL database).
6. When all AJAX requests have been resolved, the `data.lmd` file is overwritten with the new serialized `myRecordset` object.
7. A message is displayed to the user about whether all, some, or none of the records were successfully inserted into the MySQL database, and the modal box closes.


#### If this is the first time the user has logged into the site:

1. The `localStorage['initialized']` variable will be undefined.
2. This triggers the application to run the `ajaxRefresh` function (within `deqa.js`). This function sends an AJAX request to the `refreshData.php` script, which downloads information relevant to the application's functioning, and sends it back.
3. The AJAX success handler stores the data within the localStorage object.


#### If the AppCache manifest has changed OR this is the first time the user has visited the site:

1. The Application Cache is used to cache all application resources offline.
2. If the manifest file (`lastmiledata.appcache`) changes, the browser will automatically download the new resources.
3. Once the cache finishes download new resources, the application refreshes to "swap the cache" and load the new version of the application.

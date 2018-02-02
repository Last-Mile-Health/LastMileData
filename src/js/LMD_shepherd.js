// Module:          LMD_shepherd.js
// Author:          Avi Kenny
// Last update:     2018-02-02
// Dependencies:    Shepherd.js
// Purpose:         Takes the user on a virtual tour of the data portal
// Notes:           Called from page_dataPortal.js

var LMD_shepherd = (function(){


    // PRIVATE VARS
    var tour_main;      // Reference to the "main tour"
    var tour_basics;     // Reference to the "basic tour"
    var tour_programs;  // Reference to the "programs tour"
    var tour_rme;       // Reference to the "RM&E tour"
    
    
    // PRIVATE: Create a new tour
    function createTour_main() {
        
        // Declare new tour
        tour_main = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        // Start
        tour_main.addStep({
            text: 'Dig data? Take a tour of the Data Portal by clicking START...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Start',
                    action: function(){
                        tour_main.next();
                    }
                }
            ]
        });

        // Choose tour
        tour_main.addStep({ 
            title: 'Last Mile Data - Tours',
            text: 'Which tour would you like to take?',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Basics',
                    action: function(){
                        tour_main.cancel();
                        tour_basics.start();
                        
                        // Log tour usage
                        LMD_utilities.logUsage('Basics tour', 'START');
                    }
                },
                {
                    text: 'Programs',
                    action: function(){
                        tour_main.cancel();
                        tour_programs.start();
                        
                        // Log tour usage
                        LMD_utilities.logUsage('Programs tour', 'START');
                    }
                }
            ]
        });

    }
    
    
    // PRIVATE: Create a new tour
    function createTour_basics() {
        
        // Declare new tour
        tour_basics = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        // Start tour
        tour_basics.addStep({
            title: 'Last Mile Data - basics tour',
            text: 'Welcome to <i>Last Mile Data</i>, the primary programmatic database platform for Last Mile Health. This is an interactive tour created by the RM&E team to introduce you to the platform. Go ahead and click NEXT to continue',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#page_deqa').fadeOut().fadeIn().fadeOut().fadeIn();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Last Mile Data - overview',
            text: 'Last Mile Data is used by the RM&E team to enter data even when there is no internet connection',
            attachTo: '#page_deqa bottom',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('div[data-id=step-5]').attr('max-width','20%');
                        $('#page_dataPortal').fadeOut().fadeIn().fadeOut().fadeIn();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Last Mile Data - overview',
            text: 'This is the <i>Data Portal</i>, where you can access information about LMH\'s programs. It\'s updated on the 15th of each month. Let\'s take a look...',
            attachTo: '#page_dataPortal bottom',
            classes: 'shepherd-theme-lmd shepherd-thin',
            buttons: [
                {
                    text: 'Next',
                    classes: 'shepherd-thin',
                    action: function(){
                        tour_basics.next();
                        $('#dp_sidebar').fadeOut().fadeIn().fadeOut().fadeIn();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'The sidebar',
            text: 'This is the sidebar. It organizes content into groups. This is the tool you will use to navigate through the Data Portal content.',
            attachTo: '#id_10 left',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_4').fadeOut().fadeIn().fadeOut().fadeIn(function(){
                            $('#id_4').click();
                        });
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'The sidebar',
            text: 'When you click on a group on the sidebar, you will see a list of reports and tools in that group. We\'re going to look at the <i>Executive Dashboard</i> first, which is in the <i>Monthly Dashboards</i> group.',
            attachTo: '#id_4 left',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_5').click();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Executive dashboard',
            text: 'The <i>Executive dashboard</i> contains some of Last Mile Health\'s most important organizational indicators. Go ahead and scroll down to get a sense of how the Executive Team tracks success.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_7078').click();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Scale dashboard',
            text: 'There are many different data reports on Last Mile Data, such as the <i>Scale Dashboard</i>, which contains information on key scale indicators. We\'re not going to review them all now, but check them out when you have the time.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $("#mainContainer").animate({ scrollTop: 200 }, 500);
                        $('.downloadData').fadeOut().fadeIn().fadeOut().fadeIn();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Download data',
            text: 'Think you can make a prettier graph than RM&E? Every chart has a "Download Data" button, which allows you to access the underlying data in CSV format (open it with MS Excel).',
            attachTo: '.downloadData right',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('.downloadChart').fadeOut().fadeIn().fadeOut().fadeIn();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Download chart',
            text: 'You can also download the chart itself if you want to use it in a presentation or elsewhere.',
            attachTo: '.downloadChart right',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            text: 'Alright, let\'s looks at some of the other sections of the Data Portal...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_688').click();
                        $('#id_7940').click();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Detailed program reports',
            text: 'While the Monthly Dashboards group contains mostly high-level aggregated figures, the <i>Detailed Program Reports</i> group contains information disaggregated at lower levels. Here\'s a report showing basic information on active CHSSs.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_26').click();
                        $('#id_27').click();
                        
                        // Hide the info box temporarily
                        var hideInfoBox = function(){
                            setTimeout(function(){
                                console.log($('.leaflet-control'));
                                $('.leaflet-control').hide();
                                $(window).unbind('DP_loaded',hideInfoBox);
                            },100);
                        };
                        $(window).bind('DP_loaded',hideInfoBox);
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'Maps',
            text: 'The <i>Maps</i> group contains both dynamic indicator maps and links to static PDF maps produced by RM&E...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_1123').click();
                        $('#id_7279').click();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'RM&E Documents',
            text: 'All important documents produced by the RM&E team are stored in the <i>RM&E Documents</i> group.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_9690').click();
                        $('#id_1735').click();
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            title: 'LMH Documents',
            text: 'Other teams have also started to use Last Mile Data to store documents for easy organization and access.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_1').click();
                        $('#id_4').click();
                        
                        // Log tour usage
                        LMD_utilities.logUsage('Basics tour', 'FINISHED');
                    }
                }
            ]
        });
        
        tour_basics.addStep({
            text: 'That\'s just skimming the surface of the tools that Last Mile Data has to offer. We hope you spend some time digging deeper into the different available tools and reports!<br><br>Thanks for completing the tour. If you\'ve made it this far, congratulations - you\'ve just won a free bowl of home-cooked palm butter from Nick Gordon! Email Nick to claim your prize...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Finish',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
    }
    
    
    // PRIVATE: Create a new tour
    function createTour_programs() {
        
        // Declare new tour
        tour_programs = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        // Start tour
        tour_programs.addStep({
            title: 'Last Mile Data - programs tour',
            text: 'Last Mile Data has some powerful tools for analyzing programmatic data. Let\'s take a look at these tools and reports...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_4').fadeOut().fadeIn().fadeOut().fadeIn(function(){
                            $('#id_4').click();
                        });
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Monthly Dashboards',
            text: 'The Monthly Dashboard offers several different bird\'s eye views of our program.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_7078').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Scale Dashboard',
            text: 'First, the Scale Dashboard shows how many CHWs have been deployed and how many communities LMH serves.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_7363').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'CHW Activities',
            text: 'The CHW Activities report shows LMH\'s different treatment metrics. Scroll down to see more detailed breakdowns of this data.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_3169').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Supervision Activities',
            text: 'Here we can see the data on our Supervisors including their reported number of supervision visits, and CHW attendance.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_631').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Births + Deaths',
            text: 'Like the title says, this page shows several different reports on births and deaths, all broken down by CHW cohort.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_3301').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Program Outcomes',
            text: 'This report shows summary information on vaccinations, facility-based delivery rates, and antenatal care rates. This is currently restricted to Konobo; information from Gboe-Ploe and Rivercess will be available soon...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_998').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Restock Summary',
            text: 'This report shows summary data of our restock. The first indicator, shows what percentage of CHWs have all essential commodities in stock. The following reports show stock outs by individual commodities.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_688').fadeOut().fadeIn().fadeOut().fadeIn(function(){
                            $('#id_688').click();
                        });
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Detailed Program Reports',
            text: 'The Detailed Program Reports drills down into our program data.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_2088').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Community - staff listing',
            text: 'This report shows a current list of LMH\'s CHWs, and their associated CHSS, IDs, gender, and community.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_3695').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'CHW Restock',
            text: 'The Detailed CHW Restock allows users to drill down and see restock data for individual CHWs',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        // Hide the info box temporarily
                        var hideInfoBox = function(){
                            setTimeout(function(){
                                console.log($('.leaflet-control'));
                                $('.leaflet-control').hide();
                                $(window).unbind('DP_loaded',hideInfoBox);
                            },100);
                        };
                        $(window).bind('DP_loaded',hideInfoBox);
                        
                        tour_programs.next();
                        $('#id_26').click();
                        $('#id_27').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Cartography',
            text: 'The indicator map is a new way to visualize LMH\'s programatic data. Here, select an indicator, a level, and a period for analysis. As you begin to play around with this new tool, we think you\'ll begin to realize how powerful, useful, and fun it can be.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_2595').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'Cartography',
            text: 'On the Static Maps page, you\'ll be able to download different maps that show roads and communities.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                        $('#id_1123').click();
                        $('#id_4373').click();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            title: 'RM&E Documents',
            text: 'On the Program Forms page, users can always download the most up-to-date versions of the forms. This should be used instead of photocopying forms or getting them from another source.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_programs.next();
                    }
                }
            ]
        });
        
        tour_programs.addStep({
            text: 'Thanks for completing the programs tour! We hope you learned something new about our data tools and will begin using them to improve your understanding of LMH\'s work and make data-driven decisions.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Finish',
                    action: function(){
                        tour_programs.next();
                    }
                }
            ]
        });
        
    }
    
    
    // PRIVATE: Create a new tour
    function createTour_rme() {
        
        // Declare new tour
        tour_rme = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        // Start tour
        tour_rme.addStep({
            title: 'Last Mile Data - RM&E tour',
            text: 'Last Mile Data has some powerful tools for analyzing programmatic data. Let\'s take a look at these tools and reports...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_rme.next();
                        $('#id_4').fadeOut().fadeIn().fadeOut().fadeIn(function(){
                            $('#id_4').click();
                        });
                        
                        // Log tour usage
                        LMD_utilities.logUsage('Programs tour', 'FINISHED');
                    }
                }
            ]
        });
        
        tour_rme.addStep({
            text: 'Thanks for completing the programs tour! We hope you learned something new about our data tools and will begin using them to improve your understanding of LMH\'s work and make data-driven decisions.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Finish',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
    }
    
    
    // PUBLIC:  Start the tour
    //          This is called (via page_dataPortal.js) whenever the user navigates to the "Overview" tab
    function start() {
        
        // If tour is currently running, cancel it
        destroy();
        
        // Create tours
        createTour_main();
        createTour_basics();
        createTour_programs();
        createTour_rme();
        
        // Start main tour
        tour_main.start();
        
    }


    // PUBLIC:  End the tour at any point
    //          This is called (via page_dataPortal.js) whenever a new tab is clicked, unless that tab is "Overview"
    function destroy() {
        
        // End tour if it exists
        try {
            Shepherd.activeTour.cancel();
        } catch(e) {}
        
    }


    // LMD_shepherd API
    return {
        start: start,
        destroy: destroy
    };
    
    
})();
// Module:          LMD_shepherd.js
// Author:          Avi Kenny
// Last update:     2016-10-29
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
//                        !!!!! stack buttons !!!!!
//                        $('.shepherd-button').style('float','right');
//                        $('.shepherd-button').after('<br><br>');
//                        $('.shepherd-content').width('100px');
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
                    }
                },
                {
                    text: 'Programs',
                    action: function(){
                        tour_main.cancel();
                        tour_programs.start();
                    }
                },
                {
                    text: 'RM&E team',
                    action: function(){
                        tour_main.cancel();
                        tour_rme.start();
                    }
                }
            ]
        });

    }
    
    
    // PRIVATE: Create a new tour
    function createTour_basics() {
        
        // Declare new tour
        tour_basics = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        // Start
        tour_basics.addStep({
            title: 'Last Mile Data - overview',
            text: 'Welcome to "Last Mile Data", the primary programmatic database platform for Last Mile Health. This is an interactive tour created by the RM&E team to introduce you to this powerful data platform. Go ahead and click NEXT to continue',
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
        
        // DEQA
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
        
        // Data Portal
        tour_basics.addStep({
            title: 'Last Mile Data - overview',
            text: 'This is the Data Portal, where you can access information about LMH\'s programs. It\'s updated on the 15th of each month. Let\'s take a look...',
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
        
        // Sidebar
        tour_basics.addStep({
            title: 'The sidebar',
            text: 'This is the sidebar. Here, we\'ll look at some different sections that RM&E organizes LMH\'s reports and data.',
            attachTo: '#id_10 left',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_1123').fadeOut().fadeIn().fadeOut().fadeIn(function(){
                            $('#id_1123').click();
                        });
                    }
                }
            ]
        });
        
        // Sidebar
        tour_basics.addStep({
            title: 'The sidebar',
            text: 'When you click on a group on the sidebar, you will see a list of reports in that group. We\'re going to look at the Executive Dashboard first, which is in the Monthly Dashboards group.',
            attachTo: '#id_1123 left',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_4').click();
                        $('#id_5').click();
                    }
                }
            ]
        });
        
        // Exec dashboard
        tour_basics.addStep({
            title: 'Executive dashboard',
            text: 'We are looking at Last Mile Health\'s most important indicators. Go ahead and scroll down to see the different indicators that the Executive Team uses',
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
        
        // Scale dashboard
        tour_basics.addStep({
            title: 'Scale dashboard',
            text: 'Similar to the Executive Dashboard, there are many different data reports on Last Mile Data. We\'re not going to review them all out now, but check them out when you have the time.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $("#mainContainer").animate({ scrollTop: 200 }, 500);
                    }
                }
            ]
        });
        
        // Download data
        tour_basics.addStep({
            title: 'Download data',
            text: 'Think you can make a prettier graph than RM&E? Every chart has a "Download Data" button, which allows you to see the underlying data in MS Excel (in CSV format).',
            attachTo: '#download_0 right',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
        // Transition
        tour_basics.addStep({
            text: 'Alright, let\'s looks at some of the other sections of the Data Portal...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_688').click();
                        $('#id_2088').click();
                    }
                }
            ]
        });
        
        // Detailed program reports: community-staff listing
        tour_basics.addStep({
            title: 'Detailed program reports',
            text: 'The Detailed Program reports have detailed information on our Program. Here\'s a report looking at our CHAs and CHSSs',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_3695').click();
                    }
                }
            ]
        });
        
        // Detailed program reports: restock
        tour_basics.addStep({
            title: 'Detailed program reports',
            text: 'We can also look at individual CHA restocks...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_10').click();
                        $('#id_7022').click();
                    }
                }
            ]
        });
        
        // Last Mile Survey
        tour_basics.addStep({
            title: 'Last Mile Survey',
            text: '...check out findings from our Last Mile Surveys...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_18').click();
                        $('#id_20').click();
                    }
                }
            ]
        });
        
        // Liberia Stats
        tour_basics.addStep({
            title: 'Liberia Stats',
            text: '...see census and health information from different counties...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_26').click();
                        $('#id_27').click();
                    }
                }
            ]
        });
        
        // Cartography (Maps)
        tour_basics.addStep({
            title: 'Cartography (Maps)',
            text: '...select data to be visualized on a map...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_1123').click();
                        $('#id_4373').click();
                    }
                }
            ]
        });
        
        // RM&E Documents
        tour_basics.addStep({
            title: 'RM&E Documents',
            text: '...always find the correct version of a program form...',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                        $('#id_7279').click();
                    }
                }
            ]
        });
        
        // RM&E Documents
        tour_basics.addStep({
            title: 'RM&E Documents',
            text: '...and find key RM&E strategic documents.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
        // Finish (A)
        tour_basics.addStep({
            text: 'And that\'s just skimming the surface of the tools that Last Mile Data has. We hope you spend some time digging deeper into the different reports and drilling into the data!',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour_basics.next();
                    }
                }
            ]
        });
        
        // Finish (B)
        tour_basics.addStep({
            text: 'Thanks for completing the tour! If you\'ve made it this far, congratulations - you\'ve just won a free home-cooked meal from Nick Gordon. Pepe soup or palm butter. Email Nick to claim your prize...',
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
        
        //
        
    }
    
    
    // PRIVATE: Create a new tour
    function createTour_rme() {
        
        // Declare new tour
        tour_rme = new Shepherd.Tour({defaults: {classes: 'shepherd-theme-lmd'}});
        
        //
        
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
//            tour_main.cancel();
//            tour_basics.cancel();
//            tour_programs.cancel();
//            tour_rme.cancel();
        } catch(e) {}
        
    }


    // LMD_shepherd API
    return {
        start: start,
        destroy: destroy
    };
    
    
})();
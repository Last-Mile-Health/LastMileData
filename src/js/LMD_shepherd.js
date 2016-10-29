// Module:          LMD_shepherd.js
// Author:          Avi Kenny
// Last update:     2016-10-29
// Dependencies:    Shepherd.js
// Purpose:         Takes the user on a virtual tour of the data portal
// Notes:           Called from page_dataPortal.js

var LMD_shepherd = (function(){


    // PRIVATE VARS
    var tour;                       // Reference to the main tour
    
    // PRIVATE: Create a new tour
    function createTour() {
        
        // Declare tour
        tour = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-theme-arrows',
                scrollTo: true
            }
        });

        // New step: Start
        tour.addStep('1', { 
            text: 'Take a tour of the Data Portal...',
//            attachTo: 'body left',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Start',
                    action: function(){
                        $('#id_4').click();
                        $('#id_5').click();
                        tour.next();
                    }
                }
            ]
        });

        // New step: Exec Dashboard
        tour.addStep('2', {
            text: 'This is the Executive dashboard. This is the Executive dashboard. This is the Executive dashboard. This is the Executive dashboard. This is the Executive dashboard.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        $('#id_7078').click();
                        tour.next();
                    }
                }
            ]
        });
        
        // New step: Scale dashboard
        tour.addStep('3', {
            text: 'This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard. This is the Scale dashboard.',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Next',
                    action: function(){
                        tour.next();
                    }
                }
            ]
        });
        
        // New step: Finish
        tour.addStep('3', {
            text: 'Thanks for taking the tour of the Last Mile Data Portal!',
            attachTo: '#shepherdAnchor top',
            buttons: [
                {
                    text: 'Finish',
                    action: function(){
                        tour.next();
                    }
                }
            ]
        });
        
    }


    // PUBLIC:  Start the tour
    //          This function activates the module
    function start() {
        
        // If tour is currently running, cancel it
        if (tour) {
            tour.cancel();
        }
        
        // Create and start tour
        createTour();
        tour.start();
        
    }


    // PUBLIC:  End the tour at any point
    //          This is called (via page_dataPortal.js) whenever a new tab is clicked, unless that tab is "Overview"
    function destroy() {
        
        // Create and start tour
        tour.cancel();
        
    }


    // LMD_shepherd API
    return {
        start: start,
        destroy: destroy
    };
    
    
})();
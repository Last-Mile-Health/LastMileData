// Module:          LMD_koREST.js
// Author:          Avi Kenny
// Last update:     2015-10-11
// Dependencies:    Knockout.js, Knockout.mapping.js, jQuery

// !!!!! Need to update variable names throughout so that "vm" represents the ViewModel, "vmData" is data table data, "vmOther" is other !!!!!

var LMD_koREST = (function() {


    // PUBLIC:  Creates a new Knockout ViewModel with REST capabilities
    //          Usage: !!!!!
    //          Params include: url, element (stored as private variables)
    function newViewModel(params) {
        return new NewViewModel(params);
    }


    // PRIVATE: Creates a new Knockout ViewModel with REST capabilities
    //          Usage: !!!!!
    //          Params include: url, element (stored as private variables)
    function NewViewModel(params) {
        this.vm;                                //  A reference to the viewModel created by fetch() or reset()
        this.url = params.url;                  //  Base URL of the REST service, set by newViewModel()
        this.element = params.element;          //  The element that knockout will bind to via applyBindings()
        this.idAttribute = params.idAttribute;  //  The attribute that uniquely identifies a row
        this.mysqlIgnore = params.mysqlIgnore;  //  An array of fields that should be ignored by all queries
        this.other = params.other || 1;         //  Object holding any other data or methods to bind (e.g. actions)
    }

    // Method (NewViewModel): Fetches data from the server via an AJAX GET request and binds data
    //          Analagous to Backbone's fetch()
    //          Params include successCallback, errorCallback
    //          !!!!! this throws an error if it's called twice !!!!!
    NewViewModel.prototype.fetch = function(params) {
        
        var self = this;
        
        // Request data from server
        $.ajax({
            url: self.url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create observable from fetched data; bind to this.element
                self.vm = ko.mapping.fromJS(data);
                
                // Assign client-side ID and REST attributes
                for(var key in self.vm()) {
                    self.vm()[key]._cid = self.assignID();
                    self.vm()[key]._add = false;
                    self.vm()[key]._change = false;
                    self.vm()[key]._destroy = false;
                }
                
                // Activate Knockout.js
                ko.applyBindings({vmData:self.vm, other:self.other}, this.element);
                
                // Run success callback
                if (typeof params.successCallback === 'function') {
                    params.successCallback();
                }
            },
            error: function(jqXHR) {
                // Run error callback
                if (typeof params.errorCallback === 'function') {
                    params.errorCallback();
                }
            }
        });
    };


    // Method (NewViewModel): Binds data
    //          Analagous to Backbone's reset()
    //          !!!!! this throws an error if it's called twice !!!!!
    //          !!!!! WET with fetch() !!!!!
    NewViewModel.prototype.reset = function(data) {

        // Create observable from fetched data; bind to this.element
        this.vm = ko.mapping.fromJS(data);

        // Assign client-side ID and REST attributes
        for(var key in this.vm()) {
            this.vm()[key]._cid = this.assignID();
            this.vm()[key]._add = false;
            this.vm()[key]._change = false;
            this.vm()[key]._destroy = false;
        }

        // Activate Knockout.js
        ko.applyBindings({vmData:this.vm, other:this.other}, this.element);

    };


    // Method (NewViewModel): Sync with the server (i.e. process all POST, PUT, and DELETE requests)
    //          Params include successCallback, errorCallback
    NewViewModel.prototype.sync = function(params) {
        
        var self = this;
        
        var requests = {
            'POST': [],
            'PUT': [],
            'DELETE': []
        };
        
        // Compile list of inserts, updates, and deletes
        for (var key in this.vm()) {
            
            var item = this.vm()[key];
            
            // Get CIDs of objects that have been newly added (unless they've been deleted)
            if (item._add === true && item._destroy === false) {
                requests.POST.push({
                    cid: item._cid
                });
            }
            
            // Get CIDs/IDs of objects that have been changed (unless they've been newly added or deleted)
            if (item._change === true && item._add === false && item._destroy === false) {
                requests.PUT.push({
                    cid: item._cid,
                    id: item[this.idAttribute]()
                });
            }
            
            // Get CIDs/IDs of objects the will be deleted (unless the've been newly added)
            if (item._add === false && item._destroy === true) {
                requests.DELETE.push({
                    id: item[this.idAttribute]()
                });
            }
            
        }
        
        // Process inserts, updates, and deletes
        for (var key in requests) {

            for (var key2 in requests[key]) {

                // Set attributes to ignore
                var ignoreArray = ["_cid","_add","_change","_destroy"];
                var ignoreMerged = ignoreArray.concat(self.mysqlIgnore);
                var mappingIgnore = { ignore: ignoreMerged };

                // Set REST URL and data objects
                switch(key) {
                    case "POST":
                        var restURL = self.url;
                        var cid = requests[key][key2].cid;
                        for (var key3 in this.vm()) {
                            if (this.vm()[key3]._cid == cid) {
                                var myData = ko.mapping.toJS(self.vm()[key3],mappingIgnore);
                            }
                        }
                        break;
                    
                    case "PUT":
                        var restURL = self.url + requests[key][key2].id;
                        var cid = requests[key][key2].cid;
                        for (var key3 in this.vm()) {
                            if (this.vm()[key3]._cid == cid) {
                                var myData = ko.mapping.toJS(self.vm()[key3],mappingIgnore);
                            }
                        }
                        break;
                    
                    case "DELETE":
                        var restURL = self.url + requests[key][key2].id;
                        var myData = 1;
                        break;
                    
                }
                
                // Send requests
                // !!!!! attach a deferred (using "when"); success/error handlers currently firing on each ajax request !!!!!
                $.ajax({
                    url: restURL,
                    method: key,
                    data: myData,
                    dataType: 'json',
                    success: function(data) {
                        // Run success callback
                        if (typeof params.successCallback === 'function') {
                            params.successCallback();
                        }
                    },
                    error: function(jqXHR) {
                        console.log("Error. jqXHR below:");
                        console.log(jqXHR);
                        // Run error callback
                        if (typeof params.errorCallback === 'function') {
                            params.errorCallback();
                        }
                    }
                });
            }
        }
        
    };


    // Method (NewViewModel): Assigns client-side unique identifiers (like Backbone)
    //          Params include successCallback, errorCallback
    NewViewModel.prototype.assignID = function() {
        this._cid = this._cid || 1;
        return this._cid++;
    };


    // Method (NewViewModel): Return a reference to the underlying ViewModel
    //          Params include successCallback, errorCallback
    NewViewModel.prototype.getVM = function() {
        return this.vm;
    };


    // Method (NewViewModel): Define model defaults
    //          !!!!! Modify this to infer parameters from result of REST GET call (as in backbone)
    NewViewModel.prototype.defineModelDefauls = function(model) {
        this.model = ko.mapping.fromJS(model);
    };


    // Method (NewViewModel): Add new model (as defined by "defineModel()") to the end of the array
    NewViewModel.prototype.addNew = function() {
        // Assign client-side id ("_cid")
        this.model._cid = this.assignID();
        this.model._add = true;
        this.model._change = false;
        this.model._destroy = false;
        this.vm.push(this.model);
    };


    // Method (NewViewModel): Remove item from array
    NewViewModel.prototype.delete = function(myCID) {
        this.vm.destroy(function(item) { return item._cid == myCID; });
    };


    // Method (NewViewModel): Mark item as changed
    NewViewModel.prototype.markAsChanged = function(myCID) {
        for (var key in this.vm()) {
            if (this.vm()[key]._cid == myCID) {
                this.vm()[key]._change = true;
            }
        }
    };


    // LMD_koREST API
    return {
        newViewModel: newViewModel
    };
    

})();

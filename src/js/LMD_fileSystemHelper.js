// Module:          LMD_fileSystemHelper.js
// Author:          Avi Kenny
// Last update:     2015-10-11
// Dependencies:    Chrome FileSystem API

LMD_fileSystemHelper = (function(){
    
    
    // PRIVATE: Stores a reference to the filesystem
    var fs;
    
    
    // PRIVATE: Request filesystem from FileSystem API
    function requestFS(successCallback) {
        
        if (fs === undefined) {
            // !!!!! encountering a deprecation message here !!!!!
            // !!!!! need an error path if user turns down request !!!!!
            navigator.webkitPersistentStorage.requestQuota(100*1024*1024, function(grantedBytes) {
                window.requestFileSystem = window.requestFileSystem || window.webkitRequestFileSystem;
                window.requestFileSystem(PERSISTENT, grantedBytes, function(returnedFS) {
                    fs = returnedFS;
                    successCallback();
                }, logError);
            });
        } else {
            successCallback();
        }
    }
    
    
    // PUBLIC: Read a file into the filesystem
    // Callback can access file contents through first argument
    // If file is blank or does not exist, "result" contains an empty string
    function readAndUseFile(fileName, successCallback) {
        
        requestFS(function(){
            
            // Read in file
            fs.root.getFile(fileName, {create:true}, function(fileEntry) {
                // Get a File object representing the file, then use FileReader to read its contents.
                fileEntry.file(function(file) {
                    
                    var reader = new FileReader();
                    reader.readAsText(file);
                    reader.onloadend = function(){ successCallback(this.result); }
                    
                }, logError);
            }, logError);
        });
    }
    
    
    // PUBLIC: Overwrites file "fileName" with contents of "blobContents"
    function createOrOverwriteFile(fileName, blobContents, successCallback) {
        
        deleteFile(fileName, function(){
            
            // Creates (or overwrite) the file with blobContents
            fs.root.getFile(fileName, {create:true}, function(fileEntry) {
                // Create a FileWriter object for our FileEntry (data.lmd)
                fileEntry.createWriter(function(fileWriter) {
                    
                    // Create a new Blob and write it to data.lmd
                    var blob = new Blob([blobContents], {type: 'text/plain'});
                    fileWriter.write(blob);
                    
                    // Assign callback and error handler
                    if (successCallback) {
                        fileWriter.onwriteend = successCallback;
                    }
                    fileWriter.onerror = logError;
                }, logError);
            }, logError);
        });
    }
    
    
    // PUBLIC: Creates a new directory
    // Optional callback; can access directory contents through first argument
    function createDirectory(dirName, successCallback) {
        
        requestFS(function(){
            
            // Creates (or overwrite) the file with blobContents
            fs.root.getDirectory(dirName, {create: true}, function(dirEntry){
                if (successCallback) {
                    successCallback(dirEntry);
                }
            });
        });
    }
    
    
    // PUBLIC: Deletes file "fileName" (if exists)
    function deleteFile(fileName, successCallback) {
        
        requestFS(function(){
            
            // The following two lines removes the file data.lmd
            fs.root.getFile(fileName, {create:true}, function(fileEntry) {
                fileEntry.remove(function() {
                    if (successCallback) {
                        successCallback();
                    }
                }, logError);
            }, logError);
        });
    }
    
    
    // PUBLIC: Description
    // First argument of successCallback will be the created object
    function readFileIntoObject(fileName, successCallback) {
        
        readAndUseFile(fileName, function(result){
            
            if (result == "") {
                // If myRecordset is empty, create an empty object
                newObject = {};
            }
            else {
                // Otherwise, parse myRecordset into object
                newObject = JSON.parse(result);
            }
            
            // Run callback
            successCallback(newObject);
        });
    }
    
    
    // !!!!! build this out !!!!!
    // !!!!! should be a helper to createOrOverwriteFile() !!!!!
    function writeObjectToFile(fileName, myObject, successCallback) {
        
        // !!!!! code !!!!!
        
    }
    
    
    // PRIVATE: Log errors
    function logError(e) {
        console.log('LMD_fileSystemHelper error:');
        console.log(e);
    }
    
    
    // LMD_fileSystemHelper API
    return {
        readAndUseFile: readAndUseFile,
        readFileIntoObject: readFileIntoObject,
        deleteFile: deleteFile,
        createOrOverwriteFile: createOrOverwriteFile,
        createDirectory: createDirectory
    };
    
})();
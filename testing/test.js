        // Use FileSystem API; request persistent storage
        window.webkitStorageInfo.requestQuota(PERSISTENT, 50.03*1024*1024, function(grantedBytes) {
            window.requestFileSystem = window.requestFileSystem || window.webkitRequestFileSystem;
            window.requestFileSystem(PERSISTENT, grantedBytes,
                // Success handler
                function(fs) {
                    // Read in file
                    fs.root.getFile('data.lmd', {}, function(fileEntry) {
                        // Get a File object representing the file, then use FileReader to read its contents.
                        fileEntry.file(function(file) {
                            var reader = new FileReader();
                            reader.onloadend = function(e) {
                                if (this.result == "" || this.result == "{}") {
                                    console.log('empty');
                                }
                                else {
                                    console.log(this.result);
                                    
                                }
                            };
                            reader.readAsText(file);
                        }, logError);
                    }, logError);
                }, logError);
        });

function logError(e) {
    console.log('logerror');
}
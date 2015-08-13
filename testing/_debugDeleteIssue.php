<!DOCTYPE html>
<html>
    <head>
        <title>DEV: Data Portal Sidebar Editor</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/rivets.bundled.min.js"></script>
        <script>
        $(document).ready(function(){
            
            var myModel = [
                {
                    "id" : "outer A",
                    "tabs" : [
                        { "id" : "inner 1" },
                        { "id" : "inner 2" }
                    ]
                }, {
                    "id" : "outer B",
                    "tabs" : [
                        { "id" : "inner 3" },
                        { "id" : "inner 4" },
                        { "id" : "inner 5" }
                    ]
                }
            ];
            
            rivets.bind($('#sidebarDIV'), {myModel: myModel});
            
//            myModel.splice(0,1);
//            myModel[0].tabs.splice(2,1);
            
            
            // Bind click handler: delete outer tabs
            $('#deleteOuter_A').click(function(){
                console.log('delete outer');
                myModel.splice(0,1);
            });

            $('#deleteInner_5').click(function(){
                console.log('delete inner');
                myModel[0].tabs.splice(2,1);
            });
            
            // Does not work
            $('.deleteOuter').click(function(){
                console.log('delete outer');
                myModel.splice(0,1);
            });

            $('.deleteInner').click(function(){
                console.log('delete inner');
                myModel[0].tabs.splice(2,1);
            });
            
        });
        
        // Given the ID of an inner/outer tab, return the current index(es) representing its position
        // Assumes that all IDs are unique, regardless of whether the tab is an "inner" or "outer" tab
        function getIndex(id, myModel) {
            
            var match = 'not found';
            
            // Test outer tabs
            for(var i=0; i<myModel.length; i++) {
                if(myModel[i].id === id) {
                    match = i;
                }
            }
            
            // Test inner tabs
            for(var i=0; i<myModel.length; i++) {
                for(var j=0; j<myModel[i].tabs.length; j++) {
                    if(myModel[i].tabs[j].id === id) {
                        match = {
                            outer: i,
                            inner: j
                        };
                    }
                }
            }
            
            return match;
        }
        
        </script>
    </head>
    <body>
        
        <div id="sidebarDIV">
            <div rv-each-sidebar="myModel" class="outerTab" rv-id="sidebar.id">
                <input rv-value="sidebar.id">
                <button class="deleteOuter">Delete</button>
                <div rv-each-tabs="sidebar.tabs" class="innerTab" rv-id="tabs.id">
                    <input rv-value="tabs.id">
                    <button class="deleteInner">Delete</button>
                </div>
                <br>
            </div>
            <button id="deleteOuter_A">Delete outer A</button>
            <button id="deleteInner_5">Delete inner 5</button>
        </div>
        
    </body>
</html>

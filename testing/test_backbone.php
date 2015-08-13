<!DOCTYPE html>
<html>
    <head>
        
        <title>Test: backbone</title>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/reusables/lib/underscore.min.js"></script>
        <script src="/reusables/lib/backbone.min.js"></script>
        <script src="/LastMileData/lib/rivets.bundled.min.js"></script>
        
        <?php
            // Initiate/configure CURL session
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

            // Echo JSON
            $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/testing/test_rest.php/api1/";
            curl_setopt($ch,CURLOPT_URL,$url1);
            $json1 = curl_exec($ch);

            // Close CURL session and echo JSON
            curl_close($ch);
            echo "<script> var model_data = $json1; </script>". "\n\n";
        ?>
        
        
        <script>
        $(document).ready(function(){
            
            console.log(model_data);
            
            // Next steps:
            //  1) solve three issues below
            //  2) build rivets.js adapter for backbone (try it on my own first)
            //  3) bootstrap app with collection.reset()
            //  4) figure out how to use router (below)
            
            rivets.configure({templateDelimiters: ['{{', '}}']});

            // Rivets-Backbone adapter
            rivets.adapters[':'] = {
                observe: function(obj, keypath, callback) {
                    obj.on('change:' + keypath, callback);
                },
                unobserve: function(obj, keypath, callback) {
                    obj.off('change:' + keypath, callback);
                },
                get: function(obj, keypath) {
                    return obj.get(keypath);
                },
                set: function(obj, keypath, value) {
                    obj.set(keypath, value);
                }
            };

//            var TestRouter = Backbone.Router.extend({
//                routes: {
//                    "one":"one",
//                    "two":"two"
//                },
//                one: function(){
//                    console.log('one!');
//                },
//                two: function(){
//                    console.log('two!');
//                }
//            });
//            
//            var testRouter = new TestRouter;
//
//            Backbone.history.start();
////            Backbone.history.start({pushState: true});

            
            
            var User = Backbone.Model.extend({
                idAttribute: "user_id",
                defaults: { age:123, name:'avi' }
             });
            
            var UserCollection = Backbone.Collection.extend({
                model: User,
                url: '/LastMileData/testing/test_rest.php/api1/'
            });
            
            var myUserCollection = new UserCollection();
            
            myUserCollection.on('remove',function(model){
                this.removeList = this.removeList || [];
                model.urlRoot = myUserCollection.url;
                this.removeList.push(model);
            });
            
            // Reset collection with data
            myUserCollection.reset(model_data);
            
            // !!!!! Issue #1: some PUT requests fail !!!!!
            // !!!!! Issue #2: non-changed properties are being sent !!!!!
            // !!!!! Issue #3: NULLs are changed to zeros !!!!!
            
            rivets.bind($('#myDIV'), {users: myUserCollection.models});
            
            $('#btn_sync').click(function(){

                // Add/edit models
                myUserCollection.each(function(data){
                    data.save({},{
//                        success: function(c,r,o){ console.log('save success!'); console.log(r); },
//                        error: function(c,r,o){ console.log('error :/'); console.log(r); }
                    });
                });

                // Remove models
                for (var key in myUserCollection.removeList) {
                    var myModel = myUserCollection.removeList[key];
                    myModel.destroy();
                }

            });

            $('#btn_add').click(function(){
                var x = new User({ username:'new user', name:'my name' });
                myUserCollection.add(x); // can also use "push"
            });

            $('#btn_pop').click(function(){
                myUserCollection.pop();
            });

//                    var removeList = [];
            $('.btn_remove').click(function(){
                var cid = $(this).attr('data-cid');
                var x = myUserCollection.remove(cid);
//                        x.urlRoot = '/LastMileData/testing/test_rest.php/api1/'; // !!!!!
//                        removeList.push(x);
            });


            // jQuery UI sortable
            $('#sortableDIV').sortable();
            $('tbody').sortable();

            $('#alert_serialized').click(function(){
                var x = $("#sortableDIV").sortable( "serialize", {key:"s"} );
                console.log(x);
            });

            $('#alert_array').click(function(){
                var x = $("#sortableDIV").sortable("toArray");
                console.log(x);
            });

            // ?????
            $('#alert_refresh').click(function(){
                $("#sortableDIV").sortable("refresh");
            });

            // ?????
            $('#alert_refreshPos').click(function(){
                $("#sortableDIV").sortable("refreshPositions");
            });
            
        });
        </script>
        
        <style>
            #sortableDIV { cursor:pointer; }
            .sortableItem { background:lightblue; padding:10px; font-weight:bold; margin:10px; height:50px; width:100px; text-align:center; float:left; box-shadow: 2px 2px 10px grey; }
            .sortableItem:hover { background:lightgreen }
            .sortableItem:active { background:lightcoral }
            /*.sortableItem:last-child { color:blue }*/
        </style>
        
    </head>
    
    <body style="padding:5px">
        <br>
        <button id="btn_sync" class="btn btn-success">Sync</button>
        <button id="btn_add" class="btn btn-success">Add</button>
        <button id="btn_pop" class="btn btn-success">Pop</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="http://localhost/LastMileData/testing/test_backbone.php/one">One (slash)</a>&nbsp;&nbsp;&nbsp;
        <a href="http://localhost/LastMileData/testing/test_backbone.php/two">Two (slash)</a>&nbsp;&nbsp;&nbsp;
        <a href="#one">One (hash)</a>&nbsp;&nbsp;&nbsp;
        <a href="#two">Two (hash)</a>
        <br><br>
        <table id="myDIV" class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Age</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr rv-each-user="users">
                    <td><input rv-value="user:user_id"></td>
                    <td><input rv-value="user:age"></td>
                    <td><input rv-value="user:name"></td>
                    <td><input rv-value="user:username"></td>
                    <td><button rv-data-cid="user.cid" class="btn_remove btn-xs btn btn-danger">Remove: {{user.cid}}</button></td>
                </tr>
            </tbody>
        </table>
        <hr>
        
        <div id="sortableDIV">
            <div id="s_1" class="sortableItem">Hey 1</div>
            <div id="s_2" class="sortableItem">Hey 2</div>
            <div id="s_3" class="sortableItem">Hey 3</div>
            <div id="s_4" class="sortableItem">Hey 4</div>
            <div id="s_5" class="sortableItem">Hey 5</div>
            <div id="s_6" class="sortableItem">Hey 6</div>
        </div>
        <div style="clear:both"></div>
        <hr>
        
        <button id="alert_serialized" class="btn btn-success">Log positions (serialized)</button>
        <button id="alert_array" class="btn btn-success">Log positions (array)</button>
        <button id="alert_refresh" class="btn btn-success">Refresh (???)</button>
        <button id="alert_refreshPos" class="btn btn-success">Refresh positions (???)</button>
        <hr>
        
    </body>
</html>

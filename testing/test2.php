<script>
<?php

        $link = mysqli_connect("localhost","lastmile_admin","LastMile14","lastmile_db") or die("Connection error " . mysqli_error($link));
        $query = "SELECT month, ebola_screened FROM lastmile_db.test_mart" or die("Error in the consult.." . mysqli_error($link));
        $result = mysqli_query($link, $query);

        echo "var myData = '[";
        while($row = mysqli_fetch_array($result)) {
            echo(json_encode($row) . ", ");
        }
        echo "{}]';";

?>
    
    console.log(myData);
    
</script>

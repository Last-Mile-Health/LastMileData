<?php

function sqlTable($query, $table, $actionCol, $infoForm, $labelArray, $excel, $backPage, $stayInFrame, $sortArrows)
// Creates a formatted table from a SQL query
// Must include PKey in $query for actioncol items to work
// $actionCol possible values: {none, V, M, D, VM, MD, VD, VMD}, where V='view', M='modify', D='delete'
// $infoForm can be the name of "infoForm_XXX.php" or "n/a"
// $labelArray should EITHER start with a blank entry OR start with array key 1
// ADMINS always have 'delete' access; USERS have 'delete' access if $actionCol='VMD'
// if $excel is true, $_SESSION['excelString'] is created, along with a "download as .xls button    !!!!! find a way to do this without using $_SESSION array
// $table argument needs to be passed if $actionCol <> 'none'; otherwise, it can be 'none'
// $stayInFrame is either 'yes' or 'no'. If 'yes', the user will stay in the fancybox iFrame when navigating back from one infoForm to another
// $sortArrows is either (a) false, in which case no sort arrows will be displayed in column headers, or (b) the first field to sort by
{
    // IF $sortArrows, append ORDER BY clause to query
    IF ($sortArrows == true)
    {
        IF ( isset($_GET['sortA']) )
        {
            $query .= " ORDER BY " ;
            $query .= $_GET['sortA'] ;
        }
        ELSEIF ( isset($_GET['sortD']) )
        {
            $query .= " ORDER BY " ;
            $query .= $_GET['sortD'] ;
            $query .= " DESC" ;
        }
        ELSE
        {
            $query .= " ORDER BY $sortArrows" ;
        }
    }
    ELSE    // might not be necessary
    {
        $query .= ";" ;
    }
    
    // Run query to get recordset
    Include("../../sias/includes/cxn.php") ;
    $result = mysqli_query($cxn, $query) or die("Failed to connect to database") ;
    $fields = mysqli_fetch_fields($result) ;
    
    IF ($sortArrows == true)
    {
        // Create new sortURL
        $sortURL = $_SERVER['REQUEST_URI'] ;
        IF (strpos($sortURL, "?") == true) { $sortURL .= "&" ; } ELSE { $sortURL .= "?" ; }
        
        // Remove current sort GET parameter
        IF (strpos($sortURL, "sortA") == true) { $sortURL = preg_replace('/([?&])'. 'sortA' .'=[^&]+(&|$)/','$1',$sortURL) ; }
        IF (strpos($sortURL, "sortD") == true) { $sortURL = preg_replace('/([?&])'. 'sortD' .'=[^&]+(&|$)/','$1',$sortURL) ; }
    }
    
    // Create/style table
    Echo "<table class='blueTable' style='margin: 0 auto; text-align:left'>" . "\n" ;
    
    // Start header row
    Echo "<tr>" . "\n" ;
    IF ($excel) { $excelString = "<table><tr>"; }
    $i=1 ;
    Foreach ($fields as $value)
    {
        IF ($value->name <> 'primaryKey')
        {
            IF ($sortArrows == true)
            {
                IF ( isset($_GET['sortA']) AND $_GET['sortA'] == $value->name) { $imgPath = '/sias/images/sortArrow_up.png' ; }
                ELSEIF ( isset($_GET['sortD']) AND $_GET['sortD'] == $value->name) { $imgPath = '/sias/images/sortArrow_down.png' ; }
                ELSE { $imgPath = '/sias/images/sortArrow_both.png' ; }
                $sortURL_A = $sortURL . "sortA=$value->name" ;
                $sortURL_D = $sortURL . "sortD=$value->name" ;
            }
            Echo "<td style='white-space:nowrap'>$labelArray[$i]&nbsp;" . "\n" ;
                IF ($sortArrows == true)
                {
                    Echo "<img usemap ='#sortMap_$value->name' id='sort' src='$imgPath'>" . "\n" ;
                    Echo "<map name='sortMap_$value->name'>" . "\n" ;
                        Echo "<area shape ='rect' coords ='0,0,12,11' href ='$sortURL_A' alt='Sort Ascending'>" . "\n" ;
                        Echo "<area shape ='rect' coords ='0,12,12,23' href ='$sortURL_D' alt='Sort Descending'>" . "\n" ;
                    Echo "</map>" . "\n" ;
                }
            Echo "</td>" . "\n" ;
            IF ($excel) { $excelString .= "<th filter=all>$labelArray[$i]</th>"; }
            $i++ ;
        }
    }
    IF ($actionCol <> 'none') { Echo "<td style='width:164px;'>Action</td>" . "\n" ; }
    Echo "</tr>" . "\n" ;
    
    IF ($excel) { $excelString .= "</tr>"; }
    
    // Begin writing table rows
    While ( $row = mysqli_fetch_assoc($result) )
    {
        Echo "<tr>" . "\n" ;
        IF ($excel) { $excelString .= "<tr>"; }
        Foreach ($row as $key => $value)
        {
            IF ($key == 'primaryKey') { $pk = $value ; }
            ELSE
            {
                Echo "<td>$value</td>" . "\n" ;
                IF ($excel) { $excelString .= "<td>$value</td>"; }
            }
        }
        IF ($actionCol <> 'none')
        {
            Echo "<td>" . "\n" ;                                                                                                        // !!!!! backPage (only change was $backURL --> $backPage) !!!!!
            IF (substr_count($actionCol,'V') >= 1) { Echo "<a class='button small blue popup' href='$infoForm?pK=$pk&table=$table&mode=VIEW&backPage=$backPage&stayInFrame=$stayInFrame'>View</a>" . "\n" ; }
            IF (substr_count($actionCol,'M') >= 1) { Echo "<a class='button small blue popup' href='$infoForm?pK=$pk&table=$table&mode=MODIFY&backPage=$backPage&stayInFrame=$stayInFrame'>Modify</a>" . "\n" ; }
            IF (substr_count($actionCol,'D') >= 1) { Echo "<a class='button small blue popup_DEL' href='/sias/phpPages/A_deleteRecord_form.php?pK=$pk&table=$table&backPage=$backPage&stayInFrame=$stayInFrame'>Delete</a>" . "\n" ; }
            Echo "</td>" . "\n" ;
        }
        Echo "</tr>" . "\n" ;
        IF ($excel) { $excelString .= "</tr>"; }
    }
    
    // Close out table
    Echo "</table>" . "\n" ;
    IF ($excel) { $excelString .= "</table>"; }
    IF ($excel) { $_SESSION['excelString'] = $excelString ; }   // !!!!! switch these two ?????
//        IF ($excel)
//        {
//            Echo "<input type='hidden' name='excelString' value='$excelString'>" ;
//        }
}

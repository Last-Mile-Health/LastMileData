<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>View xForm</title>
        <script src="../../lib/jquery.min.js"></script>
        <script src="../js/LMD_fileSystemHelper.js"></script>
        <script>
        $(document).ready(function(){
            console.log('hey');
        });
        </script>
    </head>
    <body>
        <?php
        
            // Note: This parser assumes that variable names withing the <data> element are unique (i.e. regardless of group/hierarchy structure). This can be easily changed if this is an unsafe assumption by changing the primary key of the $dataArray to the xPath.
            /* !!!!! Existing end-user functionality:   (1) generate list of questions
             *                                          (2) Generate labels
             *                                          (3) Generate select list options
             *                                          (4) 
             */
            
            // Options passed from page_deqa.html via $_POST array
            $show_labels = isset($_POST['show_labels']) ? true : false;
            $show_xPaths = isset($_POST['show_xPaths']) ? true : false;
            $show_skipLogic = isset($_POST['show_skipLogic']) ? true : false;
            $show_internalVariables = isset($_POST['show_internalVariables']) ? true : false;
            $show_calculations = isset($_POST['show_calculations']) ? true : false;
            $show_constraints = isset($_POST['show_constraints']) ? true : false;
            
            $xForm = simplexml_load_file($_FILES['modal_viewXform_fileInput']['tmp_name']);
            
            // Create associative array to hold data
            $dataArray = array();
            
            // Form questions
            $questions = $xForm->xpath("/h:html/h:head")[0]->model->instance->data[0];
            $i = 1;
            foreach($questions as $varName => $value) {
                
                // Nesting level 1
                // !!!!! Implement this recursively ?????
                $xPath = '/data/';
                $numChildren = count($value);
                if ($numChildren===0) {
                    $dataArray[$xPath . $varName] = ['varName' => $varName];
                } else {
                    $questions2 = $value->children();
                    $xPath .= $varName . '/';
                    foreach($questions2 as $varName => $value) {
                        
                        // Nesting level 2
                        $numChildren = count($value);
                        if ($numChildren===0) {
                            $dataArray[$xPath . $varName] = ['varName' => $varName];
                        } else {
                            $questions3 = $value->children();
                            $xPath .= $varName . '/';
                            foreach($questions3 as $varName => $value) {
                                
                                // Nesting level 3
                                $numChildren = count($value);
                                if ($numChildren===0) {
                                    $dataArray[$xPath . $varName] = ['varName' => $varName];
                                } else {
                                    $questions4 = $value->children();
                                    $xPath .= $varName . '/';
                                    foreach($questions4 as $varName => $value) {
                                        
                                        // Nesting level 4
                                        $numChildren = count($value);
                                        if ($numChildren===0) {
                                            $dataArray[$xPath . $varName] = ['varName' => $varName];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // Get label locations
            $labelLocations = $xForm->xpath("//*[local-name()='label']/parent::*");
            foreach($labelLocations as $key => $value) {
                $xPath = (string) $value->attributes();
                $labelLocation_string = $value->label->attributes();
                $labelLocation = getLabelLocation($labelLocation_string);
                if (isset($dataArray[$xPath])) {
                    $dataArray[$xPath]['labelLocation'] = (string) $labelLocation;
                }
            }
            
            // Get label text
            foreach($dataArray as $xPath => $value) {
                if (isset($value['labelLocation'])) {
                    $labelLocation = $value['labelLocation'];
                    $labelText = (string) $xForm->xpath("//*[local-name()='translation']/*[local-name()='text'][@id='$labelLocation']")[0]->value[0];
                    if (isset($dataArray[$xPath])) {
                        $dataArray[$xPath]['labelText'] = $labelText;
                    }
                }
            }
            
            // Get SELECT options
            $selectOptions = $xForm->xpath("//*[local-name()='select']");
            foreach($selectOptions as $selectOption) {
                $xPath = (string) $selectOption->attributes();
                $optionArray = [];
                foreach($selectOption as $key => $value) {
                    if ($key == 'item') {
                        $optName = stripPrefixLMD($value->value);
                        $optTextLocation = getLabelLocation($value->label->attributes());
                        $optText = $xForm->xpath("//*[local-name()='text'][@id='" . $optTextLocation . "']")[0]->value;
                        array_push($optionArray, [
                            'optName' => $optName,
                            'optTextLocation' => $optTextLocation,
                            'optText' => $optText
                        ]);
                    }
                }
                if (isset($dataArray[$xPath])) {
                    $dataArray[$xPath]['selectOptions'] = $optionArray;
                }
            }
            
            // Get binding info (from <bind> elements)
            foreach($dataArray as $xPath => &$value) {
                $bindings = $xForm->$xForm->xpath("//*[local-name()='bind'][@nodeset='" . $xPath . "']");
                if (isset($bindings[0])) {
                    $value['relevant'] = $bindings[0]->attributes()['relevant'];
                    $value['required'] = $bindings[0]->attributes()['required'];
                    $value['calculate'] = $bindings[0]->attributes()['calculate'];
                    $value['constraint'] = $bindings[0]->attributes()['constraint'];
                    $value['type'] = $bindings[0]->attributes()['type'];
                }
            }
            
            // Echo out results
            echo "<hr><h1>Form title: <i>" . $xForm->xpath("/h:html/h:head/h:title")[0] . "</i></h1><hr>";
            $index = 1;
            foreach($dataArray as $xPath => $value) {
                
                if (!$show_internalVariables && isInternalVariable($value['varName'])) { continue; }
                
                $labelText = isset($value['labelText']) ? $value['labelText'] : '(none)';
                $relevant = isset($value['relevant']) ? $value['relevant'] : '(none)';
                $required = isset($value['required']) ? $value['required'] : '';
                $calculate = isset($value['calculate']) ? $value['calculate'] : '';
                $constraint = isset($value['constraint']) ? $value['constraint'] : '';
                $type = isset($value['type']) ? $value['type'] : '';
                
                echo "<p>";
                echo "<h3>Variable #" . $index++ . " name: <i>" . $value['varName'] . "</i></h3>";
                echo $show_labels ? "<b>Label</b>: " . $labelText . "<br>" : "";
                echo $show_xPaths ? "<b>xPath</b>: " . $xPath . "<br>" : "";
                echo $show_skipLogic ? "<b>Skip logic</b>: " . $relevant . "<br>" : "";
                echo $show_calculations ? "<b>Calculations</b>: " . $calculate . "<br>" : "";
                if ($show_constraints) {
                    echo "<b>Constraints</b>: " . $constraint . "<br>";
                    echo "<b>Required</b>: " . $required . "<br>";
                    echo "<b>Type</b>: " . $type . "<br>";
                }
                
                if (isset($value['selectOptions'])) {
                    echo "<b>Options</b>:<ul>";
                    foreach($value['selectOptions'] as $value2) {
                        echo "<li>" . $value2['optText'] . " (" . $value2['optName'] . ")</li>";
                    }
                    echo "</ul>";
                }
                
                echo "</p>";
                
            }
            
//          For debugging:
//            echo "<br><br><b>Data Array:</b><br>";
//            print_r($dataArray);
            
            function getLabelLocation($string) {
                return substr($string, strpos($string,'(')+2, strpos($string,')')-strpos($string,'(')-3);
            }
            
            function stripPrefixLMD($string) {
                
                $first8 = substr($string, 0, 8);
                $first4 = substr($string, 0, 4);
                
                // Stripped prefixes
                $prefixArray_8 = ['LMD-VAL-', 'LMD-OPT-', 'LMD-CHK-', 'LMD-TIM-'];
                $prefixArray_4 = ['VAR-'];
                
                foreach($prefixArray_8 as $value) {
                    if ($first8 == $value) {
                        $string = substr($string, 8);
                    }
                }
                
                foreach($prefixArray_4 as $value) {
                    if ($first4 == $value) {
                        $string = substr($string, 4);
                    }
                }
                
                return $string;
            }
            
            function isInternalVariable($varName) {
                
                $systemVariables = ['LMD-DATABASE', 'LMD-TABLE', 'LMD-VAL-meta_UUID', 'LMD-VAL-meta_dataSource', 'LMD-VAL-meta_formVersion', 'LMD-VAL-meta_deviceID'];
                
                if ( in_array($varName, $systemVariables) OR substr($varName, 0, 4)=='VAR-' ) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
        ?>
    </body>
</html>

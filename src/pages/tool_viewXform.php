<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>View xForm</title>
        <script src="../../lib/jquery.min.js"></script>
        <script src="../js/LMD_fileSystemHelper.js"></script>
    </head>
    <body>
        <?php
        
            // Options passed from page_deqa.html via $_POST array
            $show_labels = isset($_POST['show_labels']) ? true : false;
            $show_names = isset($_POST['show_names']) ? true : false;
            $show_logic = isset($_POST['show_logic']) ? true : false;
            $show_internalVariables = isset($_POST['show_internalVariables']) ? true : false;
            $paperForm = $show_labels && !$show_names && !$show_logic && !$show_internalVariables ? true : false;
            
            // Create "Simple XML" object and an associative array to hold data
            $xForm = simplexml_load_file($_FILES['modal_viewXform_fileInput']['tmp_name']);
            $dataArray = array();
            
            // Get form questions
            $questions = $xForm->xpath("/h:html/h:head")[0]->model->instance->data[0];
            $i = 1;
            foreach($questions as $varName => $value) {
                
                // Nesting level 1
                // !!!!! This can be implemented more succinctly, possibly through a recursive function !!!!!
                $xPath = '/data/';
                $numChildren = count($value);
                if ($numChildren===0) {
                    $dataArray[$xPath . $varName] = ['varName' => $varName];
                } else {
                    $questions2 = $value->children();
                    $xPath2 = $xPath . $varName . '/';
                    foreach($questions2 as $varName => $value) {
                        
                        // Nesting level 2
                        $numChildren = count($value);
                        if ($numChildren===0) {
                            $dataArray[$xPath2 . $varName] = ['varName' => $varName];
                        } else {
                            $questions3 = $value->children();
                            $xPath3 = $xPath2 . $varName . '/';
                            foreach($questions3 as $varName => $value) {
                                
                                // Nesting level 3
                                $numChildren = count($value);
                                if ($numChildren===0) {
                                    $dataArray[$xPath3 . $varName] = ['varName' => $varName];
                                } else {
                                    $questions4 = $value->children();
                                    $xPath4 = $xPath3 . $varName . '/';
                                    foreach($questions4 as $varName => $value) {
                                        
                                        // Nesting level 4
                                        $numChildren = count($value);
                                        if ($numChildren===0) {
                                            $dataArray[$xPath4 . $varName] = ['varName' => $varName];
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
            
            // Get SELECT options (multiple-select)
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
            
            // Get SELECT1 options (single-select)
            $select1Options = $xForm->xpath("//*[local-name()='select1']");
            foreach($select1Options as $select1Option) {
                $xPath = (string) $select1Option->attributes();
                $optionArray = [];
                foreach($select1Option as $key => $value) {
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
                    $dataArray[$xPath]['select1Options'] = $optionArray;
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
            
            // Bug fix (hack); without this code, the last element is incorrectly displayed
            $lastElement = array_pop($dataArray);
            array_push($dataArray,$lastElement);
            
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
                echo $paperForm ? "" : "<h2>Variable #" . $index++;
                echo $show_names ? " name: <i>" . $value['varName'] . "</i></h2>" : "</h2>";
                echo ($show_names && $show_labels) ? "<b>Label</b>: " : "";
                echo $show_labels ? $labelText : "";
                echo $paperForm && in_array($type, ['xsd:int','xsd:string','xsd:date']) ? " <input>" : "";
                echo $show_labels ? "<br>" : "";
                if ($show_logic) {
                    echo "<b>xPath</b>: " . $xPath . "<br>";
                    echo "<b>Skip logic</b>: " . $relevant . "<br>";
                    echo "<b>Calculations</b>: " . $calculate . "<br>";
                    echo "<b>Constraints</b>: " . $constraint . "<br>";
                    echo "<b>Required</b>: " . $required . "<br>";
                    echo "<b>Type</b>: " . $type . "<br>";
                }
                
                if (isset($value['selectOptions'])) {
                    echo $show_names ? "<b>Options (multiple-select)</b>:<ul>" : "<ul>";
                    foreach($value['selectOptions'] as $value2) {
                        echo "<li>";
                        echo $paperForm ? "<input type='checkbox'>" : "";
                        echo $show_labels ? $value2['optText'] : "";
                        echo $show_names ? " (" . $value2['optName'] . ")" : "";
                        echo "</li>";
                    }
                    echo "</ul>";
                }
                
                if (isset($value['select1Options'])) {
                    echo $show_names ? "<b>Options (single-select)</b>:<ul>" : "<ul>";
                    foreach($value['select1Options'] as $value2) {
                        echo "<li>";
                        echo $paperForm ? "<input type='checkbox'>" : "";
                        echo $show_labels ? $value2['optText'] : "";
                        echo $show_names ? " (" . $value2['optName'] . ")" : "";
                        echo "</li>";
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
                
                $systemVariables = ['LMD-DATABASE', 'LMD-TABLE', 'LMD-VAL-meta_UUID', 'LMD-VAL-meta_dataSource', 'LMD-VAL-meta_formVersion', 'LMD-VAL-meta_deviceID','LMD-VAL-meta_autoDate','LMD-TIM-meta_dataEntry_startTime','LMD-TIM-meta_dataEntry_endTime'];
                
                // !!!!! Hack to account for sick child form
                array_push($systemVariables, 'LMD-VAL-childHasDangerSign');
                
                if ( in_array($varName, $systemVariables) || substr($varName, 0, 4)=='VAR-' ) {
                    return true;
                } else {
                    return false;
                }
                
            }
            
        ?>
    </body>
</html>

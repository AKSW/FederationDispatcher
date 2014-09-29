<?php
function generateQueryFile($fedxBase, $resultsDir, $tmpDir, $query){
    if (!mkdir($fedxBase . $resultsDir . $tmpDir)) {
        throw new Exception("Can't create temporary directory");
        return false;
    }
    // Create query file
    $queryFile = fopen($fedxBase . $resultsDir . $tmpDir . "query", 'w') or die("Can't open file for writing query.");
    fputs($queryFile, $query);
    fclose($queryFile);
    return true;
}
/*
- function for calling fedX federation engine
- arguments: 
$fedxBase - directory where FedX is installed
$fedxConfFile - endpoint configuration file
$resultsDir - result directory
$tmpDir - temporary directory where queryFile query.txt can be found
$format - preferred format {XML, JSON}

- returns array
- result['success'] - {true,false}
- result['errormsg'] - shell response of the federation engine, if there is one
- result['content'] - in case of success content of the result file
*/
function handleRequest($fedxBase, $fedxConfFile, $resultsDir, $tmpDir, $format){
    //call FedX
    $command = "cd $fedxBase && sh ./cli.sh -d ../$fedxConfFile -f $format -folder $tmpDir @q ${resultsDir}${tmpDir}query";
    $fedxResponse = shell_exec($command);
    if (isset($result['fedxResponse'])){
        $result['success'] = false;
        $result['errormsg'] = "Execution of FedX was not successfull. Library could not be found.";
        return $result;
    }
    //read whole response file into single string no matter what format is used
    $resultFile = $fedxBase . $resultsDir . $tmpDir . 'q_1.' . strtolower($format);
    if (file_exists($resultFile)) {
        $result['success'] = true;
        $result['content'] = file_get_contents($resultFile);
        return $result;
    } else {
        $result['success'] = false;
        $result['errormsg'] = "Antwortfile konnte nicht gefunden werden. Fehlermeldung der Federation Engine: $fedxResponse";
        return $result;
    }
}

function negotiate_content(){
    //content negotiation
    $accept = explode(",", $_SERVER['HTTP_ACCEPT']);
    foreach ($accept as $x) {
        if (stristr($x, 'application/sparql-results+xml')) {
            $content = "XML";
            break;
        }elseif (stristr($x, 'application/sparql-results+json')) {
            $content = "JSON";
            break;
        }else{
            $content = "HTML";
        }
    }
    return $content;
}

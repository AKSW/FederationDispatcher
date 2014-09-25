<?php
//require configuration variables
require_once 'config.php';
require_once 'functions.php';

//Post or Get not set: print formular
if(!isset($_POST['query']) and !isset($_GET['query'])){
    echo file_get_contents('formular.html');
}else {
    //processing the query
    $query = ($_POST['query']) ? $_POST['query'] : $_GET['query'];
    //generate query File
    if(!generateQueryFile($fedxBase, $resultsDir, $tmpDir, $query)){
        die("cant generate Query File, stopping script");
    }
    $content = negotiate_content();
    if($content == "XML"){
        header('Content-Type: application/sparql-results+xml');
        $result = handleRequest($fedxBase, $fedxConfFile, $resultsDir, $tmpDir, "XML");
        if(!$result['success']){
            echo "<errormessage>".$result['errormsg']."</errormsg>";
        }else{
            echo $result['content'];
        }
    }elseif($content == "JSON"){
        header('Content-Type:applicaton/sparql-results+json');
        $result = handleRequest($fedxBase, $fedxConfFile, $resultsDir, $tmpDir, "JSON");
        if(!$result['success']){
            //print JSON Error Message?
        }else{
            echo $result['content'];
        }
    }elseif($content == "HTML"){
        $result = handleRequest($fedxBase, $fedxConfFile, $resultsDir, $tmpDir, "XML");
        if(!$result['success']){
            $content = $result['errormsg'];
        }else{
            $content = $result['content'];
        }
        include 'resultpage.php';
    }
    //cleanup files
    if (!$keep) {
        exec("rm -r ${fedxBase}${resultsDir}${tmpDir}");
    }
}

?>















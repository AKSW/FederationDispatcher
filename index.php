<?php
// Default values
$fedxBase = 'FedX/';
$resultsDir = 'results/';
$tmpDir = uniqid() . '/';
$fedxConfFile = 'ubleipzig_config.ttl';
/**
 * Set keep to true, if you want to keep the query and result files after execution
 */
$keep = false;

//Post oder Get not set: print formular
if(!isset($_POST['query']) and !isset($_GET['query'])){
?>
<!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
.codeblock {
    border: 1px dashed gray;
    background: #F0F0F0;
    padding: 10px;
}
codeblock script {
    display: block;
}
</style>
</head>
<body id="top">

<form action="" method="post">
<p>Bitte Sparql Query definieren:</p><br>
<textarea name="query" cols="100" rows="10">
select distinct ?Concept where {[] a ?Concept} LIMIT 100
</textarea>
 <p><input type="submit" value="submit" /></p>
</form>
</body>
</html>

<?php
}else {
    //content negotiation
    $accept = explode(",", $_SERVER['HTTP_ACCEPT']);
    //initiate format as XML
    $format = "XML";
    foreach ($accept as $x) {
        if (stristr($x, 'application/sparql-results+xml')) {
            header('Content-Type: application/sparql-results+xml');
            $header = "SPARQL-XML";
            break;
        } elseif (stristr($x, 'application/sparql-results+json')) {
            header('Content-Type:applicaton/sparql-results+json');
            $header = "SPARQL-JSON";
            $format = "JSON";
            break;
        }
    }
    if (!isset($header)) {
        //content must be html
        echo "<!DOCTYPE HTML><html><head></head><body><p>Ihre Ergebnisse</p>";
    }

    //processing the query
    //writing temp. queryfile for FedX
    $query = ($_POST['query']) ? $_POST['query'] : $_GET['query'];

    if (!mkdir($fedxBase . $resultsDir . $tmpDir)) {
        throw new Exception("Can't create temporary directory");
    }

    // Create query file
    $queryFile = fopen($fedxBase . $resultsDir . $tmpDir . "query", 'w') or die("Can't open file for writing query.");
    fputs($queryFile, $query);
    fclose($queryFile);

    //call FedX
    $command = "cd $fedxBase && sh ./cli.sh -d ../$fedxConfFile -f $format -folder $tmpDir @q ${resultsDir}${tmpDir}query";

    $fedxOutput = array();
    $fedxResponse = exec($command, $fedxOutput);
    if ($fedxResponse != 0) {
        echo "Execution of FedX was not successfull, returncode: $fedxResponse";
        var_dump($fedxOutput);
    }

    //read whole response file into single string no matter what format is used
    $resultFile = $fedxBase . $resultsDir . $tmpDir . 'q_1.' . strtolower($format);
    if (file_exists($resultFile)) {
        $response = file_get_contents($resultFile);
        echo "<pre>" . htmlentities($response) . "</pre>";
    } else {
        echo "Antwortfile konnte nicht gefunden werden. Fehlermeldung der Federation Engine:<br>";
        var_dump($fedxOutput);
    }
    //remove the temporary FedX response file
    if (!$keep) {
        exec("rm -r ${fedxBase}${resultsDir}${tmpDir}");
    }
    if(!isset($header)){
        echo "</body></html>";
    }
}

?>















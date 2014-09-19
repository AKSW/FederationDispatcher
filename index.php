<?php
//Post oder Get not set: print formular
if(!($_POST or $_GET)){
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
}else{
    //content negotiation
    $accept = explode(",",$_SERVER['HTTP_ACCEPT']);
    //initiate format as XML
    $format = "XML";
    foreach ($accept as $x){
        if(stristr($x,'application/sparql-results+xml')){
            header('Content-Type: application/sparql-results+xml');
            $header = "SPARQL-XML";
            break;
        }elseif(stristr($x,'application/sparql-results+json')){
            header('Content-Type:applicaton/sparql-results+json');
            $header = "SPARQL-JSON";
            $format = "JSON";
            break;
        }
    }
    if(!isset($header)){
    //content must be html
        echo "<!DOCTYPE HTML><html><head></head><body><p>Ihre Ergebnisse</p>";
    }

    //processing the query
    //writing temp. queryfile for FedX
    $query = ($_POST['query'])? $_POST['query'] : $_GET['query'];
    $tmpfilename = tempnam('/tmp','query-');
    $queryfile = fopen($tmpfilename,'w') or die;
    fputs($queryfile,$query);
    $tmpfilename=substr($tmpfilename,5);
    //call FedX
    $FedX_response = shell_exec("cd ./FedX/results && mkdir ".$tmpfilename." && cd .. && ./cli.sh -d ./../ubleipzig_config.ttl -f ".$format." -folder ".$tmpfilename." @q /../tmp/".$tmpfilename);

    fclose($queryfile);
    //read whole response file into single string no matter what format is used
    $response = file_get_contents('./FedX/results/'.$tmpfilename.'/q_1.'.strtolower($format));
    echo $response;
    //remove the temporary FedX response file
    shell_exec("cd ./FedX/results && rm -r ".$tmpfilename."/");
    if(!isset($header)){
        echo "</body></html>";
    }
}

?>















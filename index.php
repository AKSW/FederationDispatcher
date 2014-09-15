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
    //processing the query
    //writing temp. queryfile for FedX
    $query = ($_POST)? $_POST['query'] : $_GET['query'];
    $tmpfilename = tempnam('/tmp','query-');
    $queryfile = fopen($tmpfilename,'w') or die ($php_errormsg);
    fputs($queryfile,$query);
    //Aufruf von FedX
    $FedX_response = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl -f XML -folder ".$tmpfilename." @q ./..".$tmpfilename);
    fclose($queryfile);
    $response = simplexml_load_file('/FedX/response/'.$tmpfilename.'/q_1.xml');
    //we can now easily do fancy transformation stuff
    //e.g. print it
    echo $response->asXML();
    //remove the temporary FedX response file
    shell_exec("cd ./FedX && rm -r ".$tmpfilename);
    if(stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")){
        //header("Content-Type: application/xhtml+xml; charset=utf-8");
        //Format: xhtml+xml Seite
    }elseif(stristr($_SERVER['HTTP_ACCEPT'], "application/rdf+xml")){
        //header("Content-Type: text/html; charset=utf-8");
        //Format: RDF/XML
    }elseif(stristr($_SERVER['HTTP_ACCEPT'], "text/turtle")){
        //Format: Turtle
        
    }else{
        //user prob. has internet explorer?
    }
}

?>















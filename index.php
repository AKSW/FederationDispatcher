<?php
//Post oder Get not set print formular
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
    //writing queryfile for FedX
    $query = ($_POST)? $_POST['query'] : $_GET['query'];
    $queryfile = fopen("query.txt","w");
    fwrite($queryfile,$query);
    fclose($queryfile);
    //Aufruf von FedX
    $FedX_response = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl @q ./../query.txt");
    echo "<p>Antwort des Federation Dispatchers:</p></br>";
    print($FedX_response);
    
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















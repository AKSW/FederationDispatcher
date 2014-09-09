<?php
//Post oder Get nicht gesetzt? -> Ausgabe des Formulars
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
<p>Bitte Sparql Query definieren.</p><br>
<textarea name="query" cols="100" rows="10">
select distinct ?Concept where {[] a ?Concept} LIMIT 100
</textarea>
 <p><input type="submit" value="submit" /></p>
</form>
</body>
</html>

<?php
}else{
    //Verarbeitung der Query
    //1. Schreiben in Queryfile für FedX
    $query = ($_POST)? $_POST['query'] : $_GET['query'];
    $queryfile = fopen("query.txt","w");
    fwrite($queryfile,$query);
    fclose($queryfile);
    //Aufruf von FedX
    $FedX_response = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl @q ./../query.txt");
    
    //todo: Content Negotiation
    echo "<p>Antwort des Federation Dispatchers:</p></br>";
    print($FedX_response);
}

?>















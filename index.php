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
    //content negotiation
    $accept = explode(",",$_SERVER['HTTP_ACCEPT']);
    foreach ($accept as $x){
        if($x == "application/xhtml+xml spaql"){
            $format = "XML";
            break;
        }elseif($x == "application/xhtml+json"){
            $format = "JSON";
            break;
        }elseif
    }
        
    //Aufruf von FedX
    $FedX_response = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl -f ".$format." -folder ".$tmpfilename." @q ./..".$tmpfilename);
    fclose($queryfile);
    //read whole response file into single string no matter what format is used
    $response = file_get_contents('/FedX/response/'.$tmpfilename.'/q_1.xml');
    echo $response;
    //remove the temporary FedX response file
    shell_exec("cd ./FedX && rm -r ".$tmpfilename);

}

?>















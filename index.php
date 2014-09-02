<!DOCTYPE HTML>
<?php
//definierte Endpoints:
$endpoints['lobit'] = "http://lobid.org/sparql/";
$endpoints['europeana'] = "http://europeana.ontotext.com/sparql/";
?>

<html>
<head>
<style type="text/css">
.codeblock {
	border: 1px dashed gray;
	background: #F0F0F0;
	padding: 10px;
}
.codeblock script {
	display: block;
}
</style>
</head>
<body id="top">

<form action="index.php" method="post">
<p>Welche Endpoints sollen einbezogen werden?</p><br>

<?php 
    //print all defined endpoints as checkboxes
    foreach($endpoints as $endpoint){
        echo "<input type=\"checkbox\" name=\"endpoint[]\" value=\"".$endpoint."\">".$endpoint."<br>\n";
    }
?>

<textarea name="query" cols="100" rows="10">
<?php 
		if (isset($_POST['query'])){
			echo $_POST['query'];
		}else{
			echo "select distinct ?Concept where {[] a ?Concept} LIMIT 100";
		};
?>
</textarea>
 <p><input type="submit" value="submit" /></p>
</form>
</body>
</html>

<?php
    if($_POST){
    $query = $_POST['query'];
    $queryfile = fopen("query.txt","w");
    fwrite($queryfile,$query);
    fclose($queryfile);
    //write the config file for FedX
    $config = fopen("ubleipzig_config.ttl","w");
    fwrite($config, "@prefix fluid: <http://fluidops.org/config#>.\n\n");
    if(!empty($_POST['endpoint'])){
        foreach($_POST['endpoint'] as $endpoint){
            fwrite($config, "<http://".array_search($endpoint, $endpoints)."> fluid:store \"SPARQLEndpoint\";\n");
            fwrite($config, "fluid:SPARQLEndpoint \"".$endpoint."\".\n\n");
        }
    fclose($config);
    }
    
    $FedX_response = shell_exec("cd ./FedX && ./cli.sh -d ./../ubleipzig_config.ttl @q ./../query.txt");
    echo "<p>Antwort des Federation Dispatchers:</p></br>";
    print($FedX_response);
}

?>















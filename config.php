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
<?php
if(!$_POST){
?>
<form action="" method="post">
<p>Welche Endpoints sollen einbezogen werden?</p><br>
<?php 
    //print all defined endpoints as checkboxes
    foreach($endpoints as $endpoint){
        echo "<input type=\"checkbox\" name=\"endpoint[]\" value=\"".$endpoint."\">".$endpoint."<br>\n";
    }
?>

 <p><input type="submit" value="submit" /></p>
</form>
<?php
    }else{
        //write the config file for FedX
        $config = fopen("ubleipzig_config.ttl","w");
        fwrite($config, "@prefix fluid: <http://fluidops.org/config#>.\n\n");
        if(!empty($_POST['endpoint'])){
            foreach($_POST['endpoint'] as $endpoint){
                fwrite($config, "<http://".array_search($endpoint, $endpoints)."> fluid:store \"SPARQLEndpoint\";\n");
                fwrite($config, "fluid:SPARQLEndpoint \"".$endpoint."\".\n\n");
            }
        fclose($config);
        echo "<p>Konfiguration abgeschlossen.</p>";
        }
    }
?>
</body>
</html>

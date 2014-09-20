FederationDispatcher
====================

This Project is about enabling federated sparql queries on systems of the UbLeipzig to be used on some of the internal sparql-Endpoints. Therefore a simplified Interface to automatically resolve the context of the federated sparql query is needed.

Dependencies
============

A [FedX](http://www.fluidops.com/de/unternehmen/training/open_source.php) installation has to be placed in a subdirectory `FedX` of this project.

Problems with FedX
============

 The library uses primarily Ask-Queries for the source selection of the Endpoint. Because the latest DBpedia policies do not allow Ask-Queries to be used on the DBpedia Endpoint it is not possible to link it to the DBpedia.
 further discussion:
 https://groups.google.com/forum/#!topic/iwb-discussion/Fb6SwQRWdv4

Configuration
===========
to use this program, you will need 
- A FedX installation in a subdirectory called`FedX/`
- A directory called `FedX/results/` with writing permission for the PHP runtime
- A federation configuration file (see FedX manual and examples) called `ubleipzig_config.ttl`. An example file is provided as `ubleipzig_config.ttl.dist`.

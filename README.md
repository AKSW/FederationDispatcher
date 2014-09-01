FederationDispatcher
====================


This Project is about enabling federated sparql queries on systems of the UbLeipzig to be used on some of the internal sparql-Endpoints. Therefore a simplified Interface to automatically resolve the context of the federated sparql query is needed.

dependencies
============

`FedX`.http://www.fluidops.com/fedx/

Problems with FedX
============

 The library uses primarily Ask-Queries for the source selection of the Endpoint. Because the latest DBpedia policies do not allow Ask-Queries to be used on the DBpedia Endpoint it is not possible to link it to the DBpedia.
 further discussion:
 https://groups.google.com/forum/#!topic/iwb-discussion/Fb6SwQRWdv4

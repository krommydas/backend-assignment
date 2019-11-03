# Work In Progress

A slim framework based php implementation of the Vessels Track API. The implementation is close to **90%** completed. There are some missing items like querying the API with a timestamp and supporting more media type responses.

# Installation

* An apache server (*this repo folder must be included in the public folder*)
* A mongoDB installation (*a database: **BackendAssignment** with **requests**, ***shipLocations** collections have to exist *)
* PHP version 7 (* with the mongoDB extention module *)
* Composer

# Supported Requests

* Query (**GET**) the API at: *serverAddress:/backend-assignment/shipLocations/{queryParams}* (*json or csv returned content, use header *)
* Update (**POST**) the API at: *serverAddress:/backend-assignment/shipLocations* (*with a json payload of items*)



# Vessels Tracks API

Your task is to create a **RESTful API** that serves vessel tracks from a raw vessel positions data-source.
The raw data is supplied as a JSON file that you must import to a database schema of your choice.

Fields supplied are:
* **mmsi**: unique vessel identifier
* **status**: AIS vessel status
* **station**: receiving station ID
* **speed**: speed in knots x 10 (i.e. 10,1 knots is 101)
* **lon**: longitude
* **lat**: latitude
* **course**: vessel's course over ground
* **heading**: vessel's true heading
* **rot**: vessel's rate of turn
* **timestamp**: position timestamp

**The API end-point must:**
* Support the following filters: 
  * **mmsi** (single or multiple)
  * **latitude** and **longitude range** (eg: minLat=1&maxLat=2&minLon=3&maxLon=4)
  * as well as **time interval**.
* Log incoming requests to a datastore of  your choice (plain text, database, third party service etc.)
* Limit requests per user to **10/hour**. (Use the request remote IP as a user identifier)
* Support the following content types:
  * At least two of the following: application/json, application/vnd.api+json, application/ld+json, application/hal+json
  * application/xml
  * text/csv

**Share your work:**
* Stage your solution on a demo page or
* Fork this repo and create a pull request that contains your implementation in a new branch named after you.

**Have fun!**

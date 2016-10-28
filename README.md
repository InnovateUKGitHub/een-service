# Enterprise Europe Network (Alpha Version)

## Service Project

This project has for goal to:
- nightly sync data from external database.
- Handle opportunities search with elasticsearch.
- Handle events search with elasticsearch.
- Create/Retrieve contact on Salesforce
- Send Mails using [gov-delivery][1]

Documentation
-------------

This project is based on [Zend Framework 3][2] and is a RESTfull Api.
The structure of the project is separated by modules:
#### Contact

This module manage the contact by creating them, retrieving them, associate them with other object (EOI/Events)
List of the existing routes:
- Create a lead:
    - Method: POST
    - Route: /lead
- Create a contact:
    - Method: POST
    - Route: /contact
- Send contact email verification:
    - Method: POST
    - Route: /email-verification
- Create EOI:
    - Method: POST
    - Route: /eoi
- Register to event:
    - Method: POST
    - Route: /contact/event

#### Mail

This module is in charge of managing the mails through the govdelivery api.
List of the existing routes:
- Send/GET Mail:
    - Method: POST/GET
    - Route: /email/:id
- Create/Update/Delete Template:
    - Method: POST/PUT/DELETE
    - Route: /templates/email/:id

#### Search

This module interact directly with elastic search and allow to search events and opportunities.
List of the existing routes:
- Get Country list:
    - Method: GET
    - Route: /countries
- Search Events:
    - Method: POST
    - Route: /events
- Get Event:
    - Method: GET
    - Route: /events/:id
- Search Opportunities:
    - Method: POST
    - Route: /opportunities
- Get Opportunities:
    - Method: GET
    - Route: /opportunities/:id

#### Sync

This module is used to nightly sync the opportunities and events to elastic search.
It is a console only module and is used by cronjob at the moment.
It is plan to modify it when the production server is available in aws and change it to endpoint http calls.
This is possible by just changing the route configuration and the 2 controllers in charge of importing and deleting old data.
This module is also in charge to nightly send the email alert to Salesforce.
Here is the list of command available at this time:
- php public/index.php import --index=opportunity --month=1|2|3|4|5|6|7|8|9|10|11|12
This action import the selected last month of profile into elastic search from Merlin.

- php public/index.php import --index=event
This action import in elastic search the event from multiple source (Salesforce/Eventbrite/Merlin)

- php public/index.php delete --index=opportunity
This action delete the out of data in the opportunity index

- php public/index.php delete --index=event
This action delete the out of data in the event index

- php public/index.php purge
This action purge all the index in elastic search

#### Common

This module is where the shared functionality between the module such as Http and Salesforce Connection.

The [config][11] folder is where all the configuration need by the project are:
- Elastic search
- Merlin
- Salesforce
- Eventbrite
- Global settings

Cron Jobs
---------

In order to keep up to date the information present in elastic search, at the moment a cron job is running every nights:
```
cron/cron-daily.sh
```

Command Line
------------

To help the user to use the project locally a Makefile has been created to run command fast.
Here are most important:
- make install: install/re-install completely the project
- make cc: clear the cache
- make test: run the unit test
- make import: import opportunities and events into elastic search
- make purge: Delete all the data present in elasticsearch


Deployment information
----------------------

In order to deploy the project to an environment, we are using a [jenkins][8] instance.
A [jenkins file][9] is use to define the steps of deployment below:
- Code: update the code with the latest changes
- Npm: sync all the npm modules
- Gulp: run all the gulp tasks (compile css/js/image/etc.)
- Composer: update the project dependencies
- Unit Test: run the unit test suite
- Package: compile the files
- Remote Deploy: Deploy the project to selected environment - here integration_v3
- Integration Test: Run the integration test suite

If one of the step above fail for any reason, the deployment would stop.

All the deployment script are present inside this project under the [build][10] folder

Git Information
---------------

At the moment we using git flow to version the work we have done.
Nothing as been release to master as develop is our main branch and that we do not have a live environment.

Here is a quick help to use git flow:
```
git flow feature start FEATURE_NAME # This create a new feature branch
git flow feature finish             # This release the feature branch to develop
```

Links
-----

[Website][3] |
[Drupal Project][4] | 
[Vagrant Project][5] | 
[Integration Project][6] | 
[Jira][7] | 
[Jenkins][8]

[1]: https://www.govdelivery.co.uk/
[2]: https://framework.zend.com/

[3]: https://een.int.aerian.com
[4]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-webapp/browse?at=refs%2Fheads%2Fdevelop
[5]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-vagrant/browse?at=refs%2Fheads%2Fdevelop
[6]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-integration-tests/browse?at=refs%2Fheads%2Fdevelop
[7]: https://devops.innovateuk.org/issue-tracking/secure/Dashboard.jspa
[8]: https://jenkins.aerian.com/view/een/
[9]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-service/browse/Jenkinsfile?at=refs%2Fheads%2Fdevelop
[10]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-service/browse/build?at=refs%2Fheads%2Fdevelop
[11]: https://devops.innovateuk.org/code-repository/projects/EEN/repos/een-service/browse/config?at=refs%2Fheads%2Fdevelop

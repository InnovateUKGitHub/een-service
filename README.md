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
- Contact: This module manage the contact by creating them, retrieving them, associate them with other object (EOI/Events)
- Mail: This module is in charge to send mail using the gov-delivery api.
- Search: This module interact directly with elastic search and allow to search events and opportunities
- Common: This module is where the shared functionality between the module sit.

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

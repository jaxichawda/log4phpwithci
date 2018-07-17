# log4php with codeigniter working demo
Use third party tool "lo4php" for error logging in codeigniter framework.

## Installation
1. Database sql file in db folder. In this database there is a log table in which all the logs will be stored.
2. Open /application/config/database.php and edit with your database settings
3. Download the log4php library from its official website and put that in application/third_party folder
4. Create two main files : one is xml file which also called the configuration file named multiple.xml and the other one is php file named lib_log.php.
5. xml file should be in application/config folder and php file should be in application/libraries folder.
6. There is a little bit of change in core files of log4php library:
    - application\third_party\ci_log4php\layouts\LoggerLayoutHtml.php
    - application\third_party\ci_log4php\appenders\LoggerAppenderPDO.php

## Usage
It is used to dump error log in html file as well as in database.

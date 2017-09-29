# bubblekeeper
Bubble Keeper for bariatric diet

To use this site you will need PHP and MYSQL

Create a mysql database then run the .sql file to create the appropriate tables

You will also need a file called bubblesconfig.ini and change the php files to point to that file. I put it right above the webroot

The .ini file should be like this:

	mysqlhost = localhost or the host of your mysql database
	mysqluser = a username for the mysql user that has select, insert, and update priveledges
	mysqlpassword = a password for this user (in "")
	mysqldbname = the name of the database. I use meals

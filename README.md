[![Coverage Status](https://coveralls.io/repos/metator/application/badge.png?branch=master)](https://coveralls.io/r/metator/application?branch=master)
[![Build Status](https://travis-ci.org/metator/application.png?branch=master)](https://travis-ci.org/metator/application)

Metator
=======
[A unit tested shopping cart](http://metator.com). Check out the [demo site](http://demo.metator.com/).

#Install
````
composer create-project metator/application
php public/index.php phinx setup
php public/index.php phinx migrate
````

#Sample Data
To create sample data, use the command line interface of the application:
````
./metator sample products --number=1,000,000
````
You will receive output like this:
````
Created 1,000,000 sample products
All Done. Took 33.8217s
````
Anything non numeric in the number flag is stripped. Run `./metator` without flags for comprehensive usage information.

#Performance Tuning
There is a symlink from `./public/images` to `./data/images`. The application works without this, but configure your server to follow symlinks for a performance boost.

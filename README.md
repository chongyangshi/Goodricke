# Goodricke

A reminder scheduling service to help you take care of cleaning days in halls of residence.

**This is the source code of the site. To simply subscribe to the reminder service, please visit [Goodricke](https://goodricke.ebornet.com/).**

## Deployment

Goodricke should be deployed to a server running Apache/Nginx, PHP and SQLite. Please ensure that your server's time and timezone are set correctly, so that reminders will be sent out at the correct times.

Git clone or download the repository to your virtual host's file directory.

**It is important to block off access to the `.sqlite` file. Directory of the repository already contains the `.htaccess` file for Apache servers. For Nginx servers, please move `conf/goodricke.conf` to an appropriate location and make Nginx include it in the virtual host's configuration file.**

Rename `config.sample.php` to `config.php`, and configure it as stated in the script's comments.

You can obtain reCaptcha API keys from [here](https://www.google.com/recaptcha/intro/index.html), and obtain Mailgun API keys from [here](https://mailgun.com/).

Make sure that database `Goodricke.sqlite` is writable by the webserver.

Finally, create a crontab job to execute the email sendings every day at a given time. An example is given in `conf/crontab-example.txt`, which sends emails every day at 8:15 pm.

## Questions and Feedback

This service is still in its alpha state. Issues, Pull Requests and Feedbacks are very welcome. You can also contact me at shi[AT]ebornet.com .




<p align="center"><img src="https://avatars1.githubusercontent.com/u/25158?s=200&v=4"></p>


## Boardroom Booker

This is a boardroom scheduling interface.

REQUIREMENTS
------------
- PHP 5.6 or higher
- Enabled short tags in php.ini
- Enabled mod_rewrite on apache or analog on nginx

### Deploying

- Import database from ./db/dump.sql

- Add next lines to apache virtual host or similar to nginx

~~~
<Directory <project_dir> >
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
</Directory>
~~~

- You may login as a next user:

~~~
username: john_cena
password: john_cena
~~~

- Or register own.

### Application configuration

Config files located in ./application/Config

Rename file: ./application/Config/db.php.sample 
to ./application/Config/db.php 
and change file according your database configuration.

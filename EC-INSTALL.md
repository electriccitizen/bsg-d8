Backstop Generator Demo
=======================
Reviewed by Wilbur, 2023-02-09

# Project Details

- **NAME:**  bsg-d8
- **LOCAL URL:** http://bsg-d8.docksal.site
- **BRANCH:** main
- **HOSTING:** none

## Requirements and platform docs

- [EC: Local development requirements](https://docs.google.com/document/d/1_yeISu5bW5637TCeXByi82LUUfD1jeeSDHh5IeiPz4o/edit?usp=sharing)

# Local Development Setup

`cd ~/Projects`

`git clone git@github.com:electriccitizen/bsg-d8.git bsg-d8`

`cd bsg-d8/`

`fin start`

`fin hosts add`

`fin composer install`

## Download and import the database

Copies of the DB are available in the /db directory.

`gunzip -k db/dbfilename.sql.gz | fin db import`

## Install files

`tar -xvf db/2023-02-09-bsg-files.tar.gz -C web/sites/default`

`fin drush cr`

## Log into website as admin

`fin drush uli`

Open the generated login URL and you should be set to go.

# Refreshing your local environment

Whenever you start a new task, you'll want to refresh your local environment to pull in the latest changes from other developers.

`cd ~/Projects/bsg-d8`

`git checkout main`

`git pull`

`fin restart`

`fin composer install`

LOAD DB file

`fin drush cr`

`fin drush cim`

`fin drush uli`

Open the generated login URL and you should be set to go.

# Project Legend
## Docksal Images
- DB - docksal/mariadb:10.4
- CLI - docksal/cli:php7.4

See `~/Projects/bsg-d8/.docksal/docksal.yml` 

## settings.php
- database connection
- hash_salt
- base_url
- development services
- error level
- rebuild_access
- permissions_hardening
- trusted_host_pattern
- file paths

See `~/Projects/bsg-d8/web/sites/default/settings.php`

# Enabling Xdebug

Copy the `.docksal/docksal-local.yml.default` file to the .docksal folder as `docksal-local.yml` and ensure that `XDEBUG_ENABLED=1`

Open `.docksal/etc/php/php.ini` and uncomment the three lines of code directly under [xdebug]:

```
[xdebug]
xdebug.mode=debug
xdebug.discover_client_host=1
xdebug.client_host=192.168.64.100
```

Run `fin restart` to restart the Docksal project.


#[Plexis](https://github.com/Plexis/Plexis) Migrator
***
### Basic Information
Plexis migrator is a **command-line only** application written purely in PHP designed to export data from other CMSes and convert them to [Plexis](https://github.com/Plexis/Plexis)' format to allow users to migrate over with relative ease.
There is no, nor will there ever be a web interface for the migrator, everything is done on the command line.

### Requirements.
*PHP 5.3.0 or later
*PHP Phar extension
*PHP MySQLi extension
*PHP **must** be able to be run from the command line via the `php` command, doing so on Windows requires that the path to php.exe is stored in the PATH environment variable.

### Getting Started
You're more than welcome to checkout the source code and build it yourself, however we recommend getting the latest stable build from the [downloads page](https://github.com/Plexis/PlexisMigrator/downloads).

**Building from source**
In order to build the Phar from the source code, clone the repository using `git clonegit://github.com/Plexis/PlexisMigrator.git` or download the [latest zipball](https://github.com/Plexis/PlexisMigrator/zipball/master).
Once you've got the source code, go into the directory where it's located and run build.bat (on Windows) or build.sh (Linxus/OS X), or simply run `php src/build.php` on Windows or `php -f src/build.php` on Linux/OS X.

When the building is complete navigate to the 'build' directory and copy migrator.phar and migrator.phar.config.ini OR migrator.phar.config.php to your desktop or another easy to remember location.

**How to use**
To use the migrator open either migrator.phar.config.ini or migrator.phar.config.php in your text editor of choice and edit the connection information accordingly and save. You may run without either config file and you will be prompted for the connection information on startup.
To run, simple open a new Command Prompt (Windows) or Terminal (Linux/OS X) and type `php migrator.phar` and press the enter/return key, follow the on-screen instructions to complete the process.

### Supported Databases
* [MangosWeb Enhanced V3](http://code.google.com/p/mangoswebv3/)
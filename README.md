NXC Single Sign-On eZ Publish extension
-

OVERVIEW

Provides an ability to log users in once and gains access to all domains/sites without being prompted to log in again at each of them.

REQUIREMENTS

1. This extension uses nxcCache from nxc_tools eZ Publish extension, so it has to be installed too
2. All site domains must be on the same eZ Publish installation to share authorization tokens. Also all sites must have the same users.
3. And if sites use cluster configuration, all sites must have the same database, beacuse nxcCache will use cluster file handler.

INSTALLATION

1. Need to activate extensions.

settings/override/site.ini.append.php:

    [ExtensionSettings]
    ...
    ActiveExtensions[]=nxc_tools
    ActiveExtensions[]=nxc_sso

2. Generate autoloads

    $ php ./bin/php/ezpgenerateautoloads.php -e

HOW IT WORKS

There are master site and few slaves.
For example, master.com, slave1.com and slave2.com

So flow would be:

1. User visits master.com and log in
2. After that this user visits slave1.com and he is still not logged in
3. This user clicks to link http://master.com/sso/login
4. As a result the user is already logged in on slave1.com

Or

1. User visits slave2.com and he is not logged in and on master as well.
2. Clicks to link http://master.com/sso/login
3. Logs in to master.com
4. Is redirected back to slave2.com where is already logged in too.

In this case need to handle GET variable redirect_url in user/login.tpl
like:

    <input type="hidden" name="RedirectURI" value="{ezhttp( 'redirect_url', 'get' )|wash}" />

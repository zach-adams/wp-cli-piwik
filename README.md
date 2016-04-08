adrigen/wp-cli-piwik
====================

Add a `wp piwik` command to support the WP-Piwik plugin


Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using

This package implements the following commands:

### wp piwiki url



~~~
wp piwiki url 
~~~



### wp piwik token

Set auth token.

~~~
wp piwik token <token>
~~~

**OPTIONS**

	<token>
		Enter your Piwik auth token here. It is an alphanumerical code like 0a1b2c34d56e78901fa2bc3d45678efa (see WP-Piwik faq for more info)

**EXAMPLES**

    wp piwik token 0a1b2c34d56e78901fa2bc3d45678efa



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install adrigen/wp-cli-piwik`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/adrigen/wp-cli-piwik/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.

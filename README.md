# configdiff
`configdiff` is a command-line tool to compare two PHP configuration files containing configuration values defined as constants. One file being the configuration "template" or "distribution version", the other being the concrete config file. `configdiff` checks whether both files contain the same constant names and outputs the missing and/or superfluous constant names.

The script should be called as part of a deployment to guard against missing configuration values, e.g. when new values have been added to the template as part of ongoing development.

## Installation

    composer require wmde/configdiff

## Running the script
If the `vendor/bin` path (project-local or global) is in your `$PATH`, you can just call

    configdiff TEMPLATE_FILE CONFIGURATION_FILE

Otherwise you call

    php vendor/bin/configdiff.php TEMPLATE_FILE CONFIGURATION_FILE

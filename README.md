[![Build Status](https://travis-ci.com/EdisonLabs/gherphalizer.svg?branch=master)](https://travis-ci.com/EdisonLabs/gherphalizer)

# The Gherphalizer

## Overview
Provides a composer plugin that finds and transforms Gherkin files into PHP classes.

## Installation

Configure the plugin in your composer.json file using for example:
```
"extra": {
    "gherphalizer": {
        "files": [
            "*"
        ],
        "locations": [
            "app/modules",
            "app/profiles"
        ],
        "output-dir": "NOT-PUBLIC-FOLDER"
    }
}
```
Where:
- `files`: List of filenames (without the feature extension) to scan for.
- `locations`: List of paths to scan for Gherkin feature files.
- `output-dir`: The directory where the PHP files will be placed.

## How does it work
Every time you run `composer install` or `composer update`, the plugin will scan the locations for Gherkin feature files, generating PHP classes for them into the output directory.

### Command
You can also use the command `composer gherphalizer` to run the process.

Use the option `--config` to specify a config.json file to override the config defined in the `composer.json`: `composer gherphalizer --config=config.json`.

The content of the configuration file passed in needs to be in this format:
```
{
    "files": [
        "*"
    ],
    "locations": [
        "app/modules",
        "app/profiles"
    ],
    "output-dir": "NOT-PUBLIC-FOLDER"
}
```

## Contributing

Clone the repository and install all dependencies:

```
$ composer install
```

To run the tests simply:

```
$ ./vendor/bin/phpunit
```

## Notes

- Code formatting in your IDE might have an impact on the tests (how the fixture is formatted vs. the test output)

## Automated Tests and Code Sniffer
This repository integrates with [Travis CI](https://travis-ci.com/EdisonLabs/gherphalizer) to perform tests and detect PHP standards violations.

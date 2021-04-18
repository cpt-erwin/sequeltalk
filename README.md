# Sequeltalk
Simple PHP tool to help you quickly generate data for Smalltalk from an existing MySQL DB.

## Usage
To install all dependency packages used by this app run:
```
composer install
```

After you define all your tables and their columns in `public\index.php` run:
```
composer start
```

It will run an app instance on the following URL: http://localhost:8080/.

If no errors are encountered, the app will echo the resulting Smalltalk script.

## Supported Smalltalk data types
* String
* Number
* Date
* Object

## Example usage
Check the [wiki](https://github.com/tumi-soft/sequeltalk/wiki/Example-usage) for the fully
documented example usage of this tool with detailed explanation.
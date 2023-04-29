# MantisBT To-Do Lists

![PHP Requirement](https://img.shields.io/packagist/php-v/webgarden/mantisbt-todolists?style=flat-square&logo=php)
![Latest Release](https://img.shields.io/github/v/release/andrzejkupczyk/mantisbt-todolists?sort=semver&style=flat-square)
![Licence](https://img.shields.io/github/license/andrzejkupczyk/mantis-todolists?style=flat-square)

To-do lists plugin for [Mantis Bug Tracker](https://www.mantisbt.org/). 
Allows users to manage to-do tasks within a bug report. If you find this plugin useful, feel free to try [my other plugins](https://github.com/search?q=user%3Aandrzejkupczyk+topic%3Amantisbt-plugin) as well.

| MantisBT | Plugin                                                                                               |
|----------|------------------------------------------------------------------------------------------------------|
| v2.5.x   | [**latest**](https://github.com/andrzejkupczyk/mantisbt-todolists/releases/latest)                   |
| v2.x     | [v2](https://github.com/andrzejkupczyk/mantisbt-todolists/releases/tag/v2.5.0) (security fixes only) |
| v1.3.x   | [v1](https://github.com/andrzejkupczyk/mantisbt-todolists/releases/tag/v1.2.2) (unmaintained)        |

## Installation

MantisBT To-Do Lists plugin is packaged with [Composer](https://getcomposer.org/)
and uses [composer installers](https://github.com/composer/installers) library
to install the plugin in the `plugins/ToDoLists` directory:

`composer require webgarden/mantisbt-todolists`

### Old school alternative

If you prefer to avoid modifying the original MantisBT `composer.json` file,
you can follow these steps:
- download or clone the repository and place it under the MantisBT plugins folder
- rename the folder to ToDoLists
- cd into plugin's folder and run `composer install --no-dev`

## Functionality

### Issue details

[Issue details](https://user-images.githubusercontent.com/11018286/179420070-dd83f594-c935-4be5-b38e-2f771a77bc3a.webm)

### Configuration

![Configuration](https://user-images.githubusercontent.com/11018286/179420084-5d3392ad-cf12-4d4d-ae71-79a8cbd23337.png)

## Translations

Currently supported languages are:
:de:
:es:
:fr:
:gb:
:poland:
:ru:

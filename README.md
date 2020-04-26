# Symfony starter

![Symfony logo](https://user-images.githubusercontent.com/6952638/71176964-3ab4e480-226b-11ea-8522-081106cbff50.png)

**These instructions are part of the
[symfony-dev-deploy](https://github.com/RomainFallet/symfony-dev-deploy) repository.**

The purpose of this repository is to provide instructions
to create and configure a new Symfony app from scratch with
appropriate linters, assets bundler, editor config, testing
utilities, continuous integration and continuous delivery.

## Table of contents

- [Prerequisites](#prerequisites)
- [Quickstart](#quickstart)
- [Manual configuration](#manual-configuration)
  - [Init the project](#init-the-project)
  - [Install Webpack encore](#install-webpack-encore)
  - [Install TypeScript JS compiler](#install-typeScript-js-compiler)
  - [Install PostCSS CSS compiler with preset-env and PurgeCSS](#install-postcss-css-compiler-with-preset-env-and-purgecss)
  - [Install PHP Code Sniffer code formatter with PSR rules](#install-php-code-sniffer-code-formatter-with-psr-rules)
  - [Install PHP Static Analysis code linter](#install-php-static-analysis-code-linter)
  - [Install PHP Mess Detector coder linter](#install-php-mess-detector-code-linter)
  - [Install XML-Lint code linter](#install-xml-lint-code-linter)
  - [Install Prettier code formatter](#install-prettier-code-formatter)
  - [Install ESLint code linter with StandardJS rules](#install-eslint-code-linter-with-standardjs-rules)
  - [Install StyleLint code linter with Standard rules](#install-stylelint-code-linter-with-standard-rules)
  - [Install MarkdownLint code linter](#install-markdownLint-code-linter)
  - [Configure .gitignore](#configure-gitignore)
  - [Configure .editorconfig](#configure-editorconfig)
  - [Configure scripts](#configure-scripts)
  - [Configure CI with Git hooks](#configure-ci-with-git-hooks)
  - [Configure CI with GitHub Actions](#configure-ci-with-github-actions)
  - [Integrate formatters, linters & syntax to VSCode](#integrate-formatters-linters--syntax-to-vscode)
- [Usage](#usage)
  - [Launch dev server](#launch-dev-server)
  - [Watch assets changes](#watch-assets-changes)
  - [Build assets for production](#build-assets-for-production)
  - [Launch unit tests & functional tests](#launch-unit-tests--functional-tests)
  - [Check coding style & lint code for errors/bad practices](#check-coding-style--lint-code-for-errorsbad-practices)
  - [Format code automatically](#format-code-automatically)
  - [Lint code for errors/bad practices](#lint-code-for-errorsbad-practices)
  - [Execute database migrations](#execute-database-migrations)
  - [Audit & fix dependencies vulnerabilities](#audit--fix-dependencies-vulnerabilities)
  - [Check & upgrade outdated dependencies](#check--upgrade-outdated-dependencies)

## Prerequisites

- Git v2
- Symfony CLI v4
- PHP v7.3
- Composer v1.9
- MariaDB v10.4
- NodeJS v12
- Yarn v1.21

## Quickstart

```bash
# Clone repo
git clone https://github.com/RomainFallet/symfony-starter

# Go inside the project
cd ./symfony-starter

# Install dependencies
composer install && yarn install

# Create database (replace <dbname>)
# (Add "sudo" before "mysql" command on macOS and Ubuntu)
mysql -e "CREATE DATABASE <dbname>;"

# Create a user
# (replace <username>, <password>)
# (Add "sudo" before "mysql" command on macOS and Ubuntu)
mysql -e "CREATE USER <username>@localhost IDENTIFIED BY '<password>';"

# Grant him access to the db (replace <dbname> and <user>)
# (Add "sudo" before "mysql" command on macOS and Ubuntu)
mysql -e "GRANT ALL ON <dbname>.* TO <username>@localhost;"

# Load fixtures
php bin/console doctrine:fixtures:load
```

Then, copy the `./.env` file to `./.env.local` and replace variables:

```text
DATABASE_URL=mysql://<username>:<password>@127.0.0.1:3306/<dbname>
```

## Manual configuration

### Init the project

[Back to top ↑](#table-of-contents)

```bash
# Create the project
symfony new --version=~5.0.0 --full ./<my_project_name>

# Go inside the project
cd ./<my_project_name>
```

By default, packages versions are not set properly, update
your `./composer.json` to match these:

```json
  "require": {
      "php": "~7.3.0",
      "ext-ctype": "*",
      "ext-iconv": "*",
      "sensio/framework-extra-bundle": "~5.5.0",
      "symfony/asset": "~5.0.0",
      "symfony/console": "~5.0.0",
      "symfony/dotenv": "~5.0.0",
      "symfony/expression-language": "~5.0.0",
      "symfony/flex": "~1.6.0",
      "symfony/form": "~5.0.0",
      "symfony/framework-bundle": "~5.0.0",
      "symfony/http-client": "~5.0.0",
      "symfony/intl": "~5.0.0",
      "symfony/mailer": "~5.0.0",
      "symfony/monolog-bundle": "~3.5.0",
      "symfony/notifier": "~5.0.0",
      "symfony/orm-pack": "~1.0.0",
      "symfony/process": "~5.0.0",
      "symfony/security-bundle": "~5.0.0",
      "symfony/serializer-pack": "~1.0.0",
      "symfony/string": "~5.0.0",
      "symfony/translation": "~5.0.0",
      "symfony/twig-pack": "~1.0.0",
      "symfony/validator": "~5.0.0",
      "symfony/web-link": "~5.0.0",
      "symfony/yaml": "~5.0.0"
  },
  "require-dev": {
      "symfony/debug-pack": "~1.0.0",
      "symfony/maker-bundle": "~1.15.0",
      "symfony/profiler-pack": "~1.0.0",
      "symfony/test-pack": "~1.0.0"
  },
```

Then, remove `./composer-lock.json` and `./vendor` files and reinstall deps:

```bash
composer install
```

Finally, remove the file `./phpunit.xml.dist` and create a new `./phpunit.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<phpunit
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/7.5/phpunit.xsd"
  backupGlobals="false"
  colors="true"
  bootstrap="tests/bootstrap.php"
>
  <php>
    <ini name="error_reporting" value="-1" />
    <server name="APP_ENV" value="test" force="true" />
    <server name="SHELL_VERBOSITY" value="-1" />
    <server name="SYMFONY_PHPUNIT_REMOVE" value="" />
    <server name="SYMFONY_PHPUNIT_VERSION" value="7.5" />
  </php>

  <testsuites>
    <testsuite name="Project Test Suite">
      <directory>tests</directory>
    </testsuite>
  </testsuites>

  <filter>
    <whitelist processUncoveredFilesFromWhitelist="true">
      <directory suffix=".php">src</directory>
    </whitelist>
  </filter>

  <listeners>
    <listener class="Symfony\Bridge\PhpUnit\SymfonyTestsListener" />
  </listeners>
</phpunit>
```

### Install Webpack encore

[Back to top ↑](#table-of-contents)

```bash
composer require --dev symfony/webpack-encore-bundle:~1.7.0
```

Edit the `./webpack.config.js` file like this:

```javascript
const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/ts/app.ts')
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableTypeScriptLoader()
  .enablePostCssLoader()

module.exports = Encore.getWebpackConfig()
```

Replace caret (^) by tilde (~) in `./package.json` versions,
then install JS dependencies with:

```bash
yarn install
```

### Install TypeScript JS compiler

[Back to top ↑](#table-of-contents)

```bash
# Install TypeScript
yarn add -D typescript@~3.8.3

# Install TypeScript loader for Webpack
yarn add -D ts-loader@~5.4.5
```

Create a new `./tsconfig.json` file:

```json
{
  "compilerOptions": {
    "target": "ES2015",
    "module": "ES2015",
    "moduleResolution": "node",
    "strict": true,
    "sourceMap": true,
    "noUnusedLocals": true
  },
  "include": ["./assets/ts/**/*.ts"]
}
```

Remove `./assets/js` folder and then create a new `./assets/ts/app.ts` file:

```javascript
import './../css/app.css'

console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')
```

### Install PostCSS CSS compiler with preset-env and PurgeCSS

[Back to top ↑](#table-of-contents)

```bash
# Install PostCSS
yarn add -D postcss@~7.0.0

# Install PostCSS preset env
yarn add -D postcss-preset-env@~6.7.0

# Install PurgeCSS
yarn add -D purgecss@~2.1.0

# Install PurgeCSS plugin for PostCSS
yarn add -D @fullhuman/postcss-purgecss@~2.1.0

# Install PostCSS loader for Webpack
yarn add -D postcss-loader@~3.0.0
```

Create a new `./postcss.config.js` file:

```javascript
const config = {
  plugins: [
    require('postcss-preset-env')
  ]
}

if (process.env.NODE_ENV === 'production') {
  config.plugins = [
    ...config.plugins,
    require('@fullhuman/postcss-purgecss')({
      content: ['./templates/**/*.html.twig']
    })
  ]
}
```

Add browserslist to `./package.json` file:

```json
  "browserslist": [
    "> 0.5%",
    "last 2 versions",
    "Firefox ESR",
    "not dead",
    "not IE 11",
    "not IE_Mob 11",
    "not op_mini all"
  ]
```

## Install PHP Code Sniffer code formatter with PSR rules

[Back to top ↑](#table-of-contents)

```bash
composer require --dev -n squizlabs/php_codesniffer:~3.5.0
```

Create a new `./phpcs.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ruleset
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd"
>
  <arg name="basepath" value="." />
  <arg name="cache" value=".phpcs-cache" />
  <arg name="colors" />
  <arg name="extensions" value="php" />
  <rule ref="Generic.Files.LineEndings.InvalidEOLChar">
    <message>End of line character is invalid.</message>
  </rule>
  <rule ref="PSR12" />
  <rule ref="PSR1" />
  <file>src/</file>
  <file>tests/</file>
</ruleset>
```

### Install PHP Static Analysis code linter

[Back to top ↑](#table-of-contents)

```bash
composer require --dev phpstan/phpstan:~0.12.0 phpstan/phpstan-doctrine:~0.12.0 phpstan/phpstan-symfony:~0.12.0
```

Create a new `./phpstan.neon` file:

```yml
parameters:
  paths:
      - ./src
      - ./tests
  excludes_analyse:
    - ./tests/bootstrap.php
  level: max
```

### Install PHP Mess Detector code linter

[Back to top ↑](#table-of-contents)

```bash
composer require --dev phpmd/phpmd:~2.8.2
```

Create a new `./phpmd.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<ruleset name="PHPMD rule set">
  <rule ref="rulesets/cleancode.xml" />
  <rule ref="rulesets/codesize.xml" />
  <rule ref="rulesets/controversial.xml">
      <exclude name="Superglobals" />
  </rule>
  <rule ref="rulesets/design.xml" />
  <rule ref="rulesets/naming.xml" />
  <rule ref="rulesets/unusedcode.xml" />
</ruleset>
```

### Install XML-Lint code linter

[Back to top ↑](#table-of-contents)

Add this to your `./composer.json` file:

```json
  "repositories": [{
    "type": "vcs",
    "url": "https://github.com/RomainFallet/xml-lint"
  }]
```

Then, you can install it:

```bash
composer require --dev sclable/xml-lint:dev-master
```

### Install Prettier code formatter

[Back to top ↑](#table-of-contents)

```bash
# Install Prettier
yarn add -D prettier@~2.0.0

# Install Twig plugin
yarn add -D melody-runtime@~1.7.1 melody-idom@~1.7.1 prettier-plugin-twig-melody@~0.4.2

# Install XML plugin
yarn add -D @prettier/plugin-xml@~0.7.0

# Install StandardJS config
yarn add -D prettier-config-standard@~1.0.0
```

### Install ESLint code linter with StandardJS rules

[Back to top ↑](#table-of-contents)

```bash
# Install ESLint
yarn add -D eslint@~6.8.0

# Install ESLint default plugins
yarn add -D eslint-plugin-promise@~4.2.0 eslint-plugin-import@~2.20.0 eslint-plugin-node@~11.1.0

# Install TypeScript plugin
yarn add -D @typescript-eslint/eslint-plugin@~2.29.0 @typescript-eslint/parser@~2.29.0

# Install Prettier plugin
yarn add -D eslint-plugin-prettier@~3.1.0

# Install StandardJS plugin
yarn add -D eslint-plugin-standard@~4.0.0

# Install StandardJS with TypeScript configuration
yarn add -D eslint-config-standard-with-typescript@~16.0.0

# Install Prettier configuration
yarn add -D eslint-config-prettier@~6.11.0

# Install Prettier with StandardJS configuration
yarn add -D eslint-config-standard@~14.1.1 eslint-config-prettier-standard@~3.0.0
```

Create a new `./.eslintrc.json` file:

```json
{
  "extends": [
    "standard-with-typescript",
    "prettier-standard"
  ],
  "parserOptions": {
    "project": "./tsconfig.json"
  }
}
```

### Install StyleLint code linter with Standard rules

[Back to top ↑](#table-of-contents)

```bash
# Install StyleLint
yarn add -D stylelint@~13.3.3

# Install Standard configuration
yarn add -D stylelint-config-standard@~20.0.0

# Install Prettier configuration
yarn add -D stylelint-config-prettier@~8.0.0
```

Create a new  `./.stylelintrc.json`:

```bash
{
  "extends": ["stylelint-config-standard", "stylelint-config-prettier"]
}
```

### Install MarkdownLint code linter

[Back to top ↑](#table-of-contents)

```bash
yarn add -D markdownlint@~0.20.1 markdownlint-cli@~0.22.0
```

Create a new  `./.markdownlint.json` file:

```json
{
  "default": true
}
```

### Configure .gitignore

[Back to top ↑](#table-of-contents)

Add these lines to `./.gitignore` file:

```text
.phpcs-cache
src/Migrations
```

Then, remove the `/phpunit.xml` line from `./.gitignore`.

### Configure .editorconfig

[Back to top ↑](#table-of-contents)

Create a new `./.editorconfig` file :

```text
# EditorConfig is awesome: https://EditorConfig.org
root = true

[*]
end_of_line = lf
insert_final_newline = true
charset = utf-8
indent_style = space
indent_size = 2
trim_trailing_whitespace = true

[*.php]
indent_size = 4
```

### Configure scripts

[Back to top ↑](#table-of-contents)

```bash
yarn add -D npm-run-all@~4.1.5
```

Add these scripts to  `./package.json` file:

<!-- markdownlint-disable MD013 -->
```json
  "scripts": {
    "test": "php bin/phpunit",
    "lint": "npm-run-all lint:*",
    "lint:php": "./vendor/bin/phpstan analyse && ./vendor/bin/phpmd ./src,./tests text ./phpmd.xml && ./vendor/bin/phpcs",
    "lint:twig": "php bin/console lint:twig ./templates && prettier --check ./templates/**/*.html.twig",
    "lint:yml": "php bin/console lint:yaml ./config && prettier --check ./config/**/*.yaml",
    "lint:xml": "./vendor/bin/xmllint -r 0 ./ && prettier --check ./*.xml",
    "lint:ts": "eslint ./assets/ts/**/*.ts",
    "lint:css": "stylelint ./assets/css/**/*.css && prettier --check ./assets/css/**/*.css",
    "lint:json": "prettier --check ./*.json",
    "lint:md": "markdownlint ./*.md",
    "format": "npm-run-all format:*",
    "format:php": "./vendor/bin/phpcbf",
    "format:twig": "prettier --write ./templates/**/*.html.twig",
    "format:yml": "prettier --write ./config/**/*.yml",
    "format:xml": "prettier --write ./*.xml",
    "format:ts": "eslint --fix ./assets/ts/**/*.ts",
    "format:css": "stylelint --fix ./assets/css/**/*.css && prettier --write ./assets/css/**/*.css",
    "format:json": "prettier --write ./*.json",
    "format:md": "markdownlint --fix ./*.md"
  }
```
<!-- markdownlint-enable MD013 -->

### Configure CI with git hooks

[Back to top ↑](#table-of-contents)

```bash
yarn add -D husky@~4.2.0 lint-staged@~10.1.0
```

Add this to your `./package.json` file :

<!-- markdownlint-disable MD013 -->
```json
  "lint-staged": {
    "./{src,tests}/**/*.php": [
      "./vendor/bin/phpstan analyse",
      "./vendor/bin/phpmd text ./phpmd.xml",
      "./vendor/bin/phpcs"
    ],
    "./templates/**/*.html.twig": [
      "php bin/console lint:twig"
    ],
    "./config/**/*.yaml": [
      "php bin/console lint:yaml"
    ],
    "./*.xml": [
      "./vendor/bin/xmllint -r 0"
    ],
    "./assets/ts/**/*.ts": [
      "eslint"
    ],
    "./assets/css/**/*.css": [
      "stylelint"
    ],
    "./*.md": [
      "markdownlint"
    ],
    "{./templates/**/*.html.twig,./config/**/*.yaml,./*.xml,./assets/css/**/*.css,./*.json}": [
      "prettier --check"
    ]
  },
  "husky": {
    "hooks": {
      "pre-commit": "lint-staged"
    }
  }
```
<!-- markdownlint-enable MD013 -->

### Configure CI with GitHub Actions

[Back to top ↑](#table-of-contents)

Create a new `./.github/workflows/lint.yml` file:

```yaml
name: Check coding style and lint code

on: ["push"]

jobs:
  lint:
    runs-on: ubuntu-18.04

    steps:
      - uses: actions/checkout@v2
      - name: Get Composer Cache Directory
        id: composer-cache
        run: |
          echo "::set-output name=dir::$(composer config cache-files-dir)"
      - uses: actions/cache@v1
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-composer-
      - uses: actions/cache@v1
        with:
          path: ~/.npm
          key: ${{ runner.os }}-node-${{ hashFiles('**/package-lock.json') }}
          restore-keys: |
            ${{ runner.os }}-node-
      - name: Install dependencies
        run: composer install && npm ci
      - name: Check coding style and lint code
        run: yarn lint
```

Create a new `./.github/workflows/test.yml` file:

```yaml
name: Launch unit tests & functional tests

on: ["pull_request"]

jobs:
  test:
    runs-on: ubuntu-18.04

    steps:
      - uses: actions/checkout@v2
      - name: Get Composer Cache Directory
        id: composer-cache
        run: |
          echo "::set-output name=dir::$(composer config cache-files-dir)"
      - uses: actions/cache@v1
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-composer-
      - uses: actions/cache@v1
        with:
          path: ~/.npm
          key: ${{ runner.os }}-node-${{ hashFiles('**/package-lock.json') }}
          restore-keys: |
            ${{ runner.os }}-node-
      - name: Install dependencies
        run: composer install && yarn install --frozen-lockfile
      - name: Launch test
        run: yarn test
```

### Integrate formatters, linters & syntax to VSCode

[Back to top ↑](#table-of-contents)

Create a new `./.vscode/extensions.json` file:

```json
{
  "recommendations": [
    "bmewburn.vscode-intelephense-client",
    "ikappas.composer",
    "eg2.vscode-npm-script",
    "whatwedo.twig",
    "shevaua.phpcs",
    "breezelin.phpstan",
    "ecodes.vscode-phpmd",
    "esbenp.prettier-vscode",
    "dbaeumer.vscode-eslint",
    "stylelint.vscode-stylelint",
    "davidanson.vscode-markdownlint",
    "me-dutour-mathieu.vscode-github-actions",
    "mikestead.dotenv",
    "editorconfig.editorconfig",
  ]
}
```

This will suggest to install
[PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client),
[Composer](https://marketplace.visualstudio.com/items?itemName=ikappas.composer),
[NPM](https://marketplace.visualstudio.com/items?itemName=eg2.vscode-npm-script),
[Twig](https://marketplace.visualstudio.com/items?itemName=whatwedo.twig),
[PHP Code Sniffer](https://marketplace.visualstudio.com/items?itemName=shevaua.phpcs),
[PHP Static Analysis](https://marketplace.visualstudio.com/items?itemName=breezelin.phpstan),
[PHP Mess Detector](https://marketplace.visualstudio.com/items?itemName=ecodes.vscode-phpmd),
[Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode),
[ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint),
[StyleLint](https://marketplace.visualstudio.com/items?itemName=stylelint.vscode-stylelint),
[MarkdownLint](https://marketplace.visualstudio.com/items?itemName=DavidAnson.vscode-markdownlint),
[Github Actions](https://marketplace.visualstudio.com/items?itemName=me-dutour-mathieu.vscode-github-actions),
[DotENV](https://marketplace.visualstudio.com/items?itemName=mikestead.dotenv)
and [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig)
extensions to everybody opening this project in VSCode.

Then, create a new `./.vscode/settings.json` file:

```json
{
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.formatOnSave": false,
  "[twig]": {
    "editor.formatOnSave": true
  },
  "[yaml]": {
    "editor.formatOnSave": true
  },
  "[xml]": {
    "editor.formatOnSave": true
  },
  "[css]": {
    "editor.formatOnSave": true
  },
  "[json]": {
    "editor.formatOnSave": true
  },
  "eslint.enable": true,
  "stylelint.enable": true,
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true,
    "source.fixAll.stylelint": true,
    "source.fixAll.markdownlint": true
  }
}
```

This will format automatically the code on save.

## Usage

### Launch dev server

[Back to top ↑](#table-of-contents)

```bash
symfony server:start
```

### Watch assets changes

[Back to top ↑](#table-of-contents)

```bash
yarn watch
```

### Build assets for production

[Back to top ↑](#table-of-contents)

```bash
yarn build
```

### Launch unit tests & functional tests

[Back to top ↑](#table-of-contents)

```bash
yarn test
```

### Check coding style & lint code for errors/bad practices

[Back to top ↑](#table-of-contents)

```bash
# Check all files
yarn lint

# Check PHP with PHPStan, PHP Mess Detector and PHP Code Sniffer
yarn lint:php

# Check Twig with Symfony and Prettier (Standard rules)
yarn lint:twig

# Check Yaml with Symfony and Prettier
yarn lint:yml

# Check XML with XML-Lint and Prettier
yarn lint:xml

# Check TypeScript with ESLint (Prettier + StandardJS rules)
yarn lint:ts

# Check CSS with StyleLint (Standard rules) and Prettier
yarn lint:css

# Check JSON with Prettier
yarn lint:json

# Check Markdown with MarkdownLint
yarn lint:md
```

### Format code automatically

[Back to top ↑](#table-of-contents)

```bash
# Format all files
yarn lint

# Format PHP with PHP Code Sniffer
yarn format:php

# Format Twig Prettier (Standard rules)
yarn format:twig

# Format Yaml Prettier
yarn format:yml

# Format XML with Prettier
yarn format:xml

# Format TypeScript with ESLint (Prettier + StandardJS rules)
yarn format:ts

# Format CSS with StyleLint (Standard rules) and Prettier
yarn format:css

# Format JSON with Prettier
yarn format:json

# Format Markdown with MarkdownLint
yarn format:md
```

### Execute database migrations

[Back to top ↑](#table-of-contents)

```bash
# Generate migration script
php bin/console doctrine:migrations:diff

# Execute migrations
php bin/console doctrine:migrations:migrate -n
```

### Audit & fix dependencies vulnerabilities

[Back to top ↑](#table-of-contents)

```bash
# Check for known vulnerabilities in PHP dependencies
symfony security:check

# Install latest patches of all PHP dependencies
composer update

# Check for known vulnerabilities in JS dependencies
yarn audit

# Install latest patches of all JS dependencies
yarn upgrade
```

### Check & upgrade outdated dependencies

[Back to top ↑](#table-of-contents)

```bash
# Check for outdated PHP dependencies
composer outdated

# To update a dependency to a new minor/major version,
# use the require command with the specific version
composer require <dependencyName>:~X.X.X

# Check for outdated JS dependencies
yarn outdated

# Choose interactively which JS dependency to upgrade
yarn upgrade-interactive --latest
```

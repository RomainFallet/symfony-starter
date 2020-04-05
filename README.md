# Symfony starter

![Symfony logo](https://user-images.githubusercontent.com/6952638/71176964-3ab4e480-226b-11ea-8522-081106cbff50.png)

**These instructions are part of the [symfony-dev-deploy](https://github.com/RomainFallet/symfony-dev-deploy) repository.**

The purpose of this repository is to provide instructions to create and configure a new Symfony app from scratch with appropriate linter, assets bundler, editor config, continuous integration & continuous delivery on Ubuntu, macOS and Windows.

On Windows, commands are meant to be executed on PowerShell.

## Table of contents

### Create a new app with Symfony CLI

```bash
# Create the project
symfony new <my_project_name> --version=~5.0.0 --full

# Go inside the project
cd <my_project_name>
```

### Install Webpack encore

```bash
composer require --dev symfony/webpack-encore-bundle:~1.7.0
```

### Configure Webpack encore

MacOS & Ubuntu:

```bash
echo "const Encore = require('@symfony/webpack-encore')

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

module.exports = Encore.getWebpackConfig()" | tee ./webpack.config.js > /dev/null
```

Windows:

```powershell
Set-Content ./webpack.config.js "const Encore = require('@symfony/webpack-encore')

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

module.exports = Encore.getWebpackConfig()"
```

### Install JS deps

```bash
yarn install
```

### Install TypeScript

```bash
yarn add -D typescript@~3.7.0 ts-loader@~5.3.0
```

### Configure TypeScript

MacOS & Ubuntu:

```bash
echo '{
  "compilerOptions": {
    "target": "ES2015",
    "module": "ES2015",
    "moduleResolution": "node",
    "strict": true,
    "sourceMap": true,
    "noUnusedLocals": true
  },
  "include": ["./assets/ts/**/*.ts"]
}' | tee ./tsconfig.json > /dev/null
```

Windows:

```powershell
Set-Content ./webpack.config.js '{
  "compilerOptions": {
    "target": "ES2015",
    "module": "ES2015",
    "moduleResolution": "node",
    "strict": true,
    "sourceMap": true,
    "noUnusedLocals": true
  },
  "include": ["./assets/ts/**/*.ts"]
}'
```

### Configure assets

MacOS & Ubuntu:

```bash
# Create TS folder
mkdir ./assets/ts

# Remove JS folder
rm -rf ./assets/js

# Configure main TS file
echo "import '../css/app.css'

console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')" | tee ./assets/ts/app.ts > /dev/null
```

Windows:

```powershell
# Create TS folder
New-Item -ItemType Directory -Force -Path ./assets/ts

# Remove JS folder
Remove-Item -Recurse -Force ./assets/js

# Configure main TS file
Set-Content ./assets/ts/app.ts "import '../css/app.css'

console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')"
```

### Install ESLint with StandardJS rules

```bash
yarn add -D eslint@~6.8.0 eslint-plugin-standard@~4.0.0 eslint-plugin-promise@~4.2.0 eslint-plugin-import@~2.20.0 eslint-plugin-node@~11.0.0 @typescript-eslint/eslint-plugin@~2.23.0 eslint-config-standard-with-typescript@~14.0.0
```

### Configure ESLint

MacOS & Ubuntu:

```bash
echo '{
  "extends": "standard-with-typescript",
  "parserOptions": {
      "project": "./tsconfig.json"
  }
}' | tee ./.eslintrc.json > /dev/null
```

Windows:

```powershell
Set-Content ./.eslintrc.json '{
  "extends": "standard-with-typescript",
  "parserOptions": {
      "project": "./tsconfig.json"
  }
}'
```

### Install Stylelint with Standard rules

```bash
yarn add -D stylelint@~13.0.0 stylelint-config-standard@~19.0.0
```

### Configure stylelint

MacOS & Ubuntu:

```bash
echo '{
  "extends": "stylelint-config-standard"
}' | tee ./.stylelintrc.json > /dev/null
```

Windows:

```powershell
Set-Content ./.stylelintrc.jso '{
  "extends": "stylelint-config-standard"
}'
```

### Install PHP Stan

```bash
composer require --dev phpstan/phpstan:~0.12.0 phpstan/phpstan-doctrine:~0.12.0 phpstan/phpstan-symfony:~0.12.0
```

### Configure PHP Stan

MacOS & Ubuntu:

```bash
echo "parameters:
  paths:
      - ./src
      - ./tests
  level: max" | tee ./phpstan.neon > /dev/null
```

Windows:

```powershell
Set-Content ./phpstan.neon  "parameters:
  paths:
      - ./src
      - ./tests
  level: max"
```

### Install PHP Code Sniffer

```bash
composer require --dev -n squizlabs/php_codesniffer:~3.5.0
```

### Configure Code Sniffer

MacOS & Ubuntu:

```bash
echo '<?xml version="1.0" encoding="UTF-8"?>
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
</ruleset>' | tee ./phpcs.xml > /dev/null
```

Windows:

```powershell
Set-Content ./phpcs.xml '<?xml version="1.0" encoding="UTF-8"?>
<ruleset
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd"
>
  <arg name="basepath" value="." />
  <arg name="cache" value=".phpcs-cache" />
  <arg name="colors" />
  <arg name="extensions" value="php" />
  <rule ref="PSR12" />
  <rule ref="PSR1" />
  <rule ref="Generic.Files.LineEndings.InvalidEOLChar">
    <message>End of line character is invalid.</message>
  </rule>
  <file>src/</file>
  <file>tests/</file>
</ruleset>'
```

### Install PHP Mess Detector

```bash
composer require --dev phpmd/phpmd:~2.8.0
```

### Configure PHP Mess Detector

MacOS & Ubuntu:

```bash
echo '<?xml version="1.0" encoding="UTF-8" ?>
<ruleset name="PHPMD rule set"
  xmlns="http://pmd.sf.net/ruleset/1.0.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pmd.sf.net/ruleset/1.0.0
  http://pmd.sf.net/ruleset_xml_schema.xsd"
  xsi:noNamespaceSchemaLocation="
  http://pmd.sf.net/ruleset_xml_schema.xsd"
>
  <rule ref="rulesets/cleancode.xml"></rule>
  <rule ref="rulesets/codesize.xml"></rule>
  <rule ref="rulesets/controversial.xml">
      <exclude name="Superglobals" />
  </rule>
  <rule ref="rulesets/design.xml"></rule>
  <rule ref="rulesets/naming.xml"></rule>
  <rule ref="rulesets/unusedcode.xml"></rule>
  <exclude-pattern>src/Migrations</exclude-pattern>
</ruleset>' | tee ./phpmd.xml > /dev/null
```

Windows:

```powershell
Set-Content ./phpmd.xml '<?xml version="1.0" encoding="UTF-8" ?>
<ruleset name="PHPMD rule set"
  xmlns="http://pmd.sf.net/ruleset/1.0.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pmd.sf.net/ruleset/1.0.0
  http://pmd.sf.net/ruleset_xml_schema.xsd"
  xsi:noNamespaceSchemaLocation="
  http://pmd.sf.net/ruleset_xml_schema.xsd"
>
  <rule ref="rulesets/cleancode.xml"></rule>
  <rule ref="rulesets/codesize.xml"></rule>
  <rule ref="rulesets/controversial.xml">
      <exclude name="Superglobals" />
  </rule>
  <rule ref="rulesets/design.xml"></rule>
  <rule ref="rulesets/naming.xml"></rule>
  <rule ref="rulesets/unusedcode.xml"></rule>
  <exclude-pattern>src/Migrations</exclude-pattern>
</ruleset>'
```

### Configure .gitignore

MacOS & Ubuntu:

```bash
echo ".phpcs-cache
src/Migrations" | tee -a ./.gitignore
```

Windows:

```powershell
Add-Content ./.gitignore ".phpcs-cache
src/Migrations"
```

### Configure .editorconfig

MacOS & Ubuntu:

```bash
echo "# EditorConfig is awesome: https://EditorConfig.org
root = true

[*]
end_of_line = lf
insert_final_newline = true
charset = utf-8
indent_style = space
indent_size = 2
trim_trailing_whitespace = true

[*.php]
indent_size = 4" | tee ./.editorconfig > /dev/null
```

Windows:

```powershell
Set-Content ./.editorconfig "# EditorConfig is awesome: https://EditorConfig.org
root = true

[*]
end_of_line = lf
insert_final_newline = true
charset = utf-8
indent_style = space
indent_size = 2
trim_trailing_whitespace = true

[*.php]
indent_size = 4"
```

### Configure CI with pre-commit hook

MacOS & Linux:

```bash
# Add pre-commit hook
echo "#!/bin/bash
./vendor/bin/phpstan analyse || exit 1
./vendor/bin/phpmd ./src,./tests text ./phpmd.xml || exit 1
./vendor/bin/phpcs || exit 1
php bin/console lint:twig ./templates || exit 1
npx eslint ./assets/ts/**/*.ts || exit 1
npx stylelint ./assets/css/**.*css || exit 1" | tee ./.git/hooks/pre-commit > /dev/null

# Make pre-commit hook executable
chmod -x ./.git/hooks/pre-commit
```

Windows:

```powershell
Set-Content ./.git/hooks/pre-commit "Try { ./vendor/bin/phpstan analyse } Catch { Exit 1 }
Try { ./vendor/bin/phpmd ./src,./tests text ./phpmd.xml } Catch { Exit 1 }
Try { ./vendor/bin/phpcs } Catch { Exit 1 }
Try { php bin/console lint:twig ./templates } Catch { Exit 1 }
Try { npx eslint ./assets/ts/**/*.ts } Catch { Exit 1 }
Try { npx stylelint ./assets/css/**.*css } Catch { Exit 1 }"
```

### Configure CI with GitHub Actions

```bash
# Create GitHub Actions folder
mkdir -p ./.github/workflows

# Create a new "Lint" config
echo "name: Lint project

on: [pull_request]

jobs:
  phpstan:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: composer install
    - name: Check code with PHP Stan
      run: ./vendor/bin/phpstan analyse
  phpcs:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: composer install
    - name: Check code with PHP Code Sniffer
      run: ./vendor/bin/phpcs
  phpmd:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: composer install
    - name: Check code with PHP Mess Detector
      run: ./vendor/bin/phpmd ./src,./tests text ./phpmd.xml
  symfonylint-yaml:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: composer install
    - name: Check code with Yaml Symfony linter
      run: php bin/console lint:yaml ./config
  symfonylint-twig:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: composer install
    - name: Check code with Twig Symfony linter
      run: php bin/console lint:twig ./templates
  eslint:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: yarn install
    - name: Check code with ESLint
      run: npx eslint ./assets/ts/**/*.ts
  stylelint:
    runs-on: ubuntu-18.04
    steps:
    - uses: actions/checkout@v2
    - name: Install deps
      run: yarn install
    - name: Check code with StyleLint
      run: npx stylelint ./assets/css/**/*.css" | tee ./.github/workflows/lint.yml > /dev/null
```

## Usage

### Fix errors automatically

```bash
# PHP
./vendor/bin/phpcbf

# JS
npx eslint ./assets/ts/**/*.ts --fix

# CSS
npx stylelint ./assets/css/**/*.css --fix
```

### Lint the project manually

```bash
# Lint with PHPStan
./vendor/bin/phpstan analyse

# Lint with PHP Code Sniffer
./vendor/bin/phpcs

# Lint with PHP Mess Detector
./vendor/bin/phpmd ./src,./tests text ./phpmd.xml

# Lint yaml with Symfony
php bin/console lint:yaml ./config

# Lint twig with Symfony
php bin/console lint:twig ./templates

# Lint TypeScript with ESLint
npx eslint ./assets/ts/**/*.ts

# Lint CSS with StyleLint
npx stylelint ./assets/css/**/*.css
```

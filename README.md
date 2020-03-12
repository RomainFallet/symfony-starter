# Symfony starter

![Symfony logo](https://user-images.githubusercontent.com/6952638/71176964-3ab4e480-226b-11ea-8522-081106cbff50.png)

## Create a new Symfony app

```bash
# Create the project
symfony new <my_project_name> --version=~5.0.0 --full

# Go inside the project
cd <my_project_name>
```

## Install Webpack encore

```bash
# Install Webpack encore
composer require --dev symfony/webpack-encore-bundle:~1.7.0

# Configure Webpack encore
cat > ./webpack.config.js <<EOF
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

module.exports = Encore.getWebpackConfig()
EOF

# Install JS deps
yarn install

# Install TypeScript deps
yarn add -D typescript@~3.7.0 ts-loader@~5.3.0

# Configure TypeScript
cat > ./tsconfig.json <<EOF
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
EOF
```

## Configure assets

```bash
# Create TS folder
mkdir ./assets/ts

# Remove JS folder
rm -rf ./assets/js/

# Configure main TS file
cat > ./assets/ts/app.ts <<EOF
import '../css/app.css'

console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')
EOF
```

## Install ESLint

```bash
# Install ESLint
yarn add -D eslint@~6.8.0 eslint-plugin-standard@~4.0.0 eslint-plugin-promise@~4.2.0 eslint-plugin-import@~2.20.0 eslint-plugin-node@~11.0.0 @typescript-eslint/eslint-plugin@~2.23.0 eslint-config-standard-with-typescript@~14.0.0

# Configure ESLint
cat > ./.eslintrc.json <<EOF
{
  "extends": "standard-with-typescript",
  "parserOptions": {
      "project": "./tsconfig.json"
  }
}
EOF
```

## Install Stylelint

```bash
# Install stylelint
yarn add -D stylelint@~13.0.0 stylelint-config-standard@~19.0.0

# Configure stylelint
cat > ./.stylelintrc.json <<EOF
{
  "extends": "stylelint-config-standard"
}
EOF
```

## Install PHP Stan

```bash
# Install PHP Stan
composer require --dev phpstan/phpstan:~0.12.0 phpstan/phpstan-doctrine:~0.12.0 phpstan/phpstan-symfony:~0.12.0

# Configure PHP Stan
cat > ./phpstan.neon <<EOF
parameters:
  paths:
      - ./src
      - ./tests
  level: max
EOF
```

## Install PHP Code Sniffer

```bash
# Install Code Sniffer
composer require --dev -n squizlabs/php_codesniffer:~3.5.0

# Configure Code Sniffer
cat > ./phpcs.xml <<EOF
<?xml version="1.0" encoding="UTF-8"?>
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
  <file>src/</file>
  <file>tests/</file>
</ruleset>
EOF

# Configure .gitignore
echo ".phpcs-cache" >> ./.gitignore
```

## Install PHP Mess Detector

```bash
# Install PHP Mess Detector
composer require --dev phpmd/phpmd:~2.8.0

# Configure PHP Mess Detector
cat > ./phpmd.xml <<EOF
<?xml version="1.0" encoding="UTF-8" ?>
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
</ruleset>
EOF
```

## Install EditorConfig

```bash
cat > ./.editorconfig <<EOF
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
EOF
```

## Insall pre-commit hook

```bash
# Add pre-commit hook
cat > ./.git/hooks/pre-commit <<EOF
#!/bin/bash

./vendor/bin/phpstan analyse
if [ ! $? = 0 ]; then exit 1; fi
./vendor/bin/phpmd ./src,./tests text ./phpmd.xml
if [ ! $? = 0 ]; then exit 1; fi
./vendor/bin/phpcs
if [ ! $? = 0 ]; then exit 1; fi
php bin/console lint:yaml ./config
if [ ! $? = 0 ]; then exit 1; fi
php bin/console lint:twig ./templates
if [ ! $? = 0 ]; then exit 1; fi
npx eslint ./assets/ts/**/*.ts
if [ ! $? = 0 ]; then exit 1; fi
npx stylelint ./assets/css/**.*css
if [ ! $? = 0 ]; then exit 1; fi
EOF

# Make pre-commit hook executable
chmod -x ./.git/hooks/pre-commit
```

## Install CI with GitHub Actions

```bash
mkdir ./.github
mkdir ./.github/workflows
cat > ./.github/workflows/lint.yml <<EOF
name: Lint project

on: [push, pull_request]

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
      run: npx stylelint ./assets/css/**/*.css
EOF
```

## Fix existing errors

```bash
# PHP
./vendor/bin/phpcbf

# JS
npx eslint ./assets/ts/**/*.ts --fix

# CSS
npx stylelint ./assets/css/**/*.css --fix
```

## Lint usage

```bash
./vendor/bin/phpstan analyse
```

```bash
./vendor/bin/phpcs
```

```bash
./vendor/bin/phpmd ./src,./tests text ./phpmd.xml
```

```bash
php bin/console lint:yaml ./config
```

```bash
php bin/console lint:twig ./templates
```

```bash
npx eslint ./assets/ts/**/*.ts
```

```bash
npx stylelint ./assets/css/**/*.css
```

# Symfony starter

![Symfony logo](https://user-images.githubusercontent.com/6952638/71176964-3ab4e480-226b-11ea-8522-081106cbff50.png)

**These instructions are part of the [symfony-dev-deploy](https://github.com/RomainFallet/symfony-dev-deploy) repository.**

The purpose of this repository is to provide instructions to create and configure a new Symfony app from scratch with appropriate linters, assets bundler, editor config, testing utilities, continuous integration.

## Table of contents

- [Quickstart](#quickstart)
- [Manual configuration](#manual-configuration)
  - [Create a new app with Symfony CLI](#create-a-new-app-with-symfony-cli)
  - [Install Symfony testing utilities](#install-symfony-testing-utilities)
  - [Install Webpack encore](#install-webpack-encore)
  - [Install JS dependencies](#install-js-dependencies)
  - [Install TypeScript](#install-typeScript)
  - [Install PostCSS with Autoprefixer and PurgeCSS](#install-postcss-with-autoprefixer-and-purgecss)
  - [Install Prettier code formatter](#install-prettier-code-formatter)
  - [Install ESLint code linter with StandardJS rules](#install-eslint-code-linter-with-standardjs-rules)
  - [Install StyleLint code linter with Standard rules](#install-stylelint-code-linter-with-standard-rules)
  - [Install PHP Code Sniffer code formatter with PSR rules](#install-php-code-sniffer-code-formatter-with-psr-rules)
  - [Install PHP Stan code linter](#install-php-stan-code-linter)
  - [Install PHP Mess Detector coder linter](#install-php-mess-detector-code-linter)
  - [Configure .gitignore](#configure-gitignore)
  - [Configure .editorconfig](#configure-editorconfig)
- [Usage](#usage)
  - [Launch dev server](#launch-dev-server)
  - [Watch assets changes](#watch-assets-changes)
  - [Build assets for production](#build-assets-for-production)
  - [Launch unit tests & functional tests](#launch-unit-tests--functional-tests)
  - [Check coding style](#check-coding-style)
  - [Format code automatically](#format-code-automatically)
  - [Lint code for errors/bad practices](#lint-code-for-errorsbad-practices)
  - [Execute database migrations](#execute-database-migrations)

## Quickstart

```bash
git clone https://github.com/RomainFallet/symfony-starter
```

## Manual configuration

### Create a new app with Symfony CLI

[Back to top ↑](#table-of-contents)

```bash
# Create the project
symfony new <my_project_name> --version=~5.0.0 --full

# Go inside the project
cd <my_project_name>
```

### Install Symfony testing utilities

[Back to top ↑](#table-of-contents)

```bash
composer require --dev symfony/test-pack:~1.0.0
```

### Install Webpack encore

[Back to top ↑](#table-of-contents)

```bash
# Install
composer require --dev symfony/webpack-encore-bundle:~1.7.0

# Configuraton (MacOS & Ubuntu)
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
  .enablePostCssLoader()

module.exports = Encore.getWebpackConfig()" | tee ./webpack.config.js > /dev/null

# Configuration (Windows)
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
  .enablePostCssLoader()

module.exports = Encore.getWebpackConfig()"
```

### Install JS dependencies

[Back to top ↑](#table-of-contents)

```bash
yarn install
```

### Install TypeScript

[Back to top ↑](#table-of-contents)

```bash
# Install
yarn add -D typescript@~3.7.0 ts-loader@~5.3.0

# Configuration (MacOS & Ubuntu)
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


mkdir ./assets/ts

rm -rf ./assets/js

echo "import '../css/app.css'
console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')" | tee ./assets/ts/app.ts > /dev/null

# Configuration (Windows)
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

New-Item -ItemType Directory -Force -Path ./assets/ts

Remove-Item -Recurse -Force ./assets/js

Set-Content ./assets/ts/app.ts "import '../css/app.css'
console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts')"
```

### Install PostCSS with Autoprefixer and PurgeCSS

[Back to top ↑](#table-of-contents)

```bash
# Install
yarn add -D postcss@~7.0.0 autoprefixer@~9.7.0 purgecss@~2.1.0 @fullhuman/postcss-purgecss@~2.1.0

# Configuration (MacOS & Ubuntu)
echo "const config = {
  plugins: [
    require('autoprefixer')
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

module.exports = config" | tee ./postcss.config.js > /dev/null

# Configuration (Windows)
Set-Content ./postcss.config.js "const config = {
  plugins: [
    require('autoprefixer')
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

module.exports = config"
```

[Back to top ↑](#table-of-contents)

```bash
yarn add -D prettier@~2.0.0 eslint-plugin-prettier@~3.1.0 eslint-config-prettier@~6.10.0 prettier-config-standard@~1.0.0 stylelint-config-prettier@~8.0.0 prettier-plugin-twig-melody@~0.4.0 @prettier/plugin-xml@~0.7.0
```

### Install ESLint code linter with StandardJS rules

[Back to top ↑](#table-of-contents)

```bash
# Install
yarn add -D eslint@~6.8.0 eslint-plugin-standard@~4.0.0 eslint-plugin-promise@~4.2.0 eslint-plugin-import@~2.20.0 eslint-plugin-node@~11.0.0 @typescript-eslint/eslint-plugin@~2.23.0 eslint-config-standard-with-typescript@~14.0.0

# Configuration (MacOS & Ubuntu)
echo '{
  "extends": [
    "standard-with-typescript",
    "prettier-standard"
  ],
  "parserOptions": {
      "project": "./tsconfig.json"
  }
}' | tee ./.eslintrc.json > /dev/null

# Configuration (Windows)
Set-Content ./.eslintrc.json '{
  "extends": [
    "standard-with-typescript",
    "prettier-standard"
  ],
  "parserOptions": {
      "project": "./tsconfig.json"
  }
}'
```

### Install StyleLint code linter with Standard rules

[Back to top ↑](#table-of-contents)

```bash
# Install
yarn add -D stylelint@~13.0.0 stylelint-config-standard@~19.0.0

# Configuration (MacOS & Ubuntu)
echo '{
  "extends": [
    "stylelint-config-standard",
    "stylelint-config-prettier"
  ]
}' | tee ./.stylelintrc.json > /dev/null

# Configuration (Windows)
Set-Content ./.stylelintrc.jso '{
  "extends": [
    "stylelint-config-standard",
    "stylelint-config-prettier"
  ]
}'
```

### Install PHP Code Sniffer code formatter with PSR rules

[Back to top ↑](#table-of-contents)

```bash
# Install
composer require --dev -n squizlabs/php_codesniffer:~3.5.0

# Configuration (MacOS & Ubuntu)
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

# Configuration (Windows)
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

### Install PHP Stan code linter

[Back to top ↑](#table-of-contents)

```bash
# Install
composer require --dev phpstan/phpstan:~0.12.0 phpstan/phpstan-doctrine:~0.12.0 phpstan/phpstan-symfony:~0.12.0

# Configuration (MacOS & Ubuntu)
echo "parameters:
  paths:
      - ./src
      - ./tests
  level: max" | tee ./phpstan.neon > /dev/null

# Configuration (Windows)
Set-Content ./phpstan.neon  "parameters:
  paths:
      - ./src
      - ./tests
  level: max"
```

### Install PHP Mess Detector code linter

[Back to top ↑](#table-of-contents)

```bash
# Install
composer require --dev phpmd/phpmd:~2.8.0

# Configuration (MacOS & Ubuntu)
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
</ruleset>' | tee ./phpmd.xml > /dev/null

# Configuration (Windows)
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
</ruleset>'
```

### Configure .gitignore

[Back to top ↑](#table-of-contents)

```bash
# Configuration (MacOS & Ubuntu)
echo ".phpcs-cache
src/Migrations"

# Configuration (Windows)
Add-Content ./.gitignore ".phpcs-cache
src/Migrations"
```

### Configure .editorconfig

[Back to top ↑](#table-of-contents)

```bash
# Configuration (MacOS & Ubuntu)
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

# Configuration (Windows)
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

## Usage

### Launch dev server

[Back to top ↑](#table-of-contents)

```bash
symfony server:start
```

### Watch assets changes

[Back to top ↑](#table-of-contents)

```bash
yarn dev
```

### Build assets for production

[Back to top ↑](#table-of-contents)

```bash
yarn build
```

### Launch unit tests & functional tests

[Back to top ↑](#table-of-contents)

```bash
php bin/phpunit
```

### Check coding style

[Back to top ↑](#table-of-contents)

```bash
# Check PHP with PHP Code Sniffer
./vendor/bin/phpcs

# Check Twig wih Prettier
prettier --check "./templates/**/*.html.twig"

# Check Yaml with Prettier
prettier --check "{./config/**/*.yml,./phpstan.neon}"

# Check XML with Prettier
prettier --check "./*.xml"

# Check JavaScript/TypeScript with Prettier
prettier --check "{./assets/ts/**/*.ts,./webpack.config.js}"

# Check JSON with Prettier
prettier --check "./*.json"

# Check CSS with Prettier
prettier --check "./assets/css/**/*.css"
```

### Format code automatically

[Back to top ↑](#table-of-contents)

```bash
# Format PHP with PHP Code Sniffer
./vendor/bin/phpcbf

# Format Twig with Prettier
prettier --write "./templates/**/*.html.twig"

# Format Yaml with Prettier
prettier --write "{./config/**/*.yml,./phpstan.neon}"

# Format XML with Prettier
prettier --write "./*.xml"

# Format TypeScript/JavaScript with Prettier
prettier --write "{./assets/ts/**/*.ts,./webpack.config.js}"

# Format JSON with Prettier
prettier --write "./*.json"

# Format CSS with Prettier
prettier --write "./assets/css/**/*.css"
```

### Lint code for errors/bad practices

[Back to top ↑](#table-of-contents)

```bash
# Lint PHP with PHPStan
./vendor/bin/phpstan analyse

# Lint PHP with PHP Mess Detector
./vendor/bin/phpmd ./src,./tests text ./phpmd.xml

# Lint Twig with Symfony
php bin/console lint:twig ./templates

# Lint Yaml with Symfony
php bin/console lint:yaml ./config

# Lint TypeScript/JavaScrip with ESLint
npx eslint "{./assets/ts/**/*.ts,./webpack.config.js}"

# Lint CSS with StyleLint
npx stylelint ./assets/css/**/*.css
```

### Execute database migrations

[Back to top ↑](#table-of-contents)

```bash
# Generate migration script
php bin/console doctrine:migrations:diff

# Execute migrations
php bin/console doctrine:migrations:migrate -n
```

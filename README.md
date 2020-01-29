# Symfony starter

## Create a new Symfony app

```bash
symfony new <my_project_name> --version=5.0.* --full
```

## Install Webpack encore

```bash
# Install Webpack encore
composer require --dev symfony/webpack-encore-bundle:1.7.*

# Configure Webpack encore
cat > ./webpack.config.js <<EOF
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
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
;

module.exports = Encore.getWebpackConfig();
EOF

# Install JS deps
yarn install

# Install TypeScript deps
yarn add -D typescript@3.7.* ts-loader@5.3.*

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
import '../css/app.css';

console.log('Hello Webpack Encore! Edit me in assets/ts/app.ts');
EOF
```

## Install StandardJS

```bash
yarn add -D ts-standard@3.1.*
```

## Install Stylelint

```bash
# Install stylelint
yarn add -D stylelint@13.0.* stylelint-config-standard@19.0.*

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
composer require --dev phpstan/phpstan:0.12.* phpstan/phpstan-doctrine:0.12.* phpstan/phpstan-symfony:0.12.*

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
composer require --dev -n squizlabs/php_codesniffer:3.5.*

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
    <rule ref="PEAR" />
    <rule ref="MySource" />
    <rule ref="Zend" />
    <rule ref="PSR12" />
    <rule ref="Squiz" />
    <file>bin/</file>
    <file>config/</file>
    <file>public/</file>
    <file>src/</file>
    <file>tests/</file>
</ruleset>
EOF
```

## Install PHP Mess Detector

```bash
# Install PHP Mess Detector
composer require --dev phpmd/phpmd:2.8.*

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
    <rule ref="rulesets/controversial.xml"></rule>
    <rule ref="rulesets/design.xml"></rule>
    <rule ref="rulesets/naming.xml"></rule>
    <rule ref="rulesets/unusedcode.xml"></rule>
</ruleset>
EOF
```

## Usage

```bash
./vendor/bin/phpstan analyse
```

```bash
./vendor/bin/phpcs
```

```bash
./vendor/bin/phpmd ./src text ./phpmd.xml
```

```bash
php bin/console lint:yaml ./config
```

```bash
php bin/console lint:twig ./src
```

```bash
npx ts-standard
```

```bash
npx stylelint ./assets/css/**.*css
```

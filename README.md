# Magento 2 Amazon SES module

This module enhances the magento 2 capabilities to send transactional mails
with Amazon SES service.

## Installation

Add the module to your composer file.

```json
{
  "require": {
    "opsway/module-amazonses": "dev-master"
  }
}
```


Install the module with composer.

```bash

    composer update

```

On succeed, install the module via bin/magento console.

```bash

    bin/magento cache:clean

    bin/magento module:install OpsWay_EmailAmazonSES

    bin/magento setup:upgrade

```
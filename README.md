# Magento 2 B2B Utils
B2B utilities module for Magento 2.

[![Build Status](https://travis-ci.org/aune-io/magento2-b2butils.svg?branch=master)](https://travis-ci.org/aune-io/magento2-b2butils)
[![Coverage Status](https://coveralls.io/repos/github/aune-io/magento2-b2butils/badge.svg?branch=master)](https://coveralls.io/github/aune-io/magento2-b2butils?branch=master)
[![Latest Stable Version](https://poser.pugx.org/aune-io/magento2-b2butils/v/stable)](https://packagist.org/packages/aune-io/magento2-b2butils)
[![Latest Unstable Version](https://poser.pugx.org/aune-io/magento2-b2butils/v/unstable)](https://packagist.org/packages/aune-io/magento2-b2butils)
[![Total Downloads](https://poser.pugx.org/aune-io/magento2-b2butils/downloads)](https://packagist.org/packages/aune-io/magento2-b2butils)
[![License](https://poser.pugx.org/aune-io/magento2-b2butils/license)](https://packagist.org/packages/aune-io/magento2-b2butils)

## System requirements
This extension supports the following versions of Magento:

*	Community Edition (CE) versions 2.2.x
*	Enterprise Edition (EE) versions and 2.2.x

## Installation
1. Require the module via Composer
```bash
$ composer require aune-io/magento2-b2butils
```

2. Enable the module
```bash
$ bin/magento module:enable Aune_B2bUtils
$ bin/magento setup:upgrade
```

## Features
### Registration Approval
Allows a store owner to manually approve new customers before they can successfully login.

To enable this feature:
1. Go to Stores > Configuration > Customers > Customer Configuration > Create New Account Options
2. Set _Require Shop Owner Approval_ to _Yes_
3. (Optional) Set recipient for registration notification
4. (Optional) Customise email templates
5. Clean the configuration cache

### Reserved Prices
Show prices only to registered / logged in users.

To enable this feature:
1. Go to Stores > Configuration > Catalog > Catalog > Price
2. Set _Reserved to registered users_ to _Yes_
3. Clean the configuration cache

## Authors, contributors and maintainers

Author:
- [Renato Cason](https://github.com/renatocason)

## License
Licensed under the Open Software License version 3.0

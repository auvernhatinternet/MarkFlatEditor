![Packagist Version](https://img.shields.io/packagist/v/auvernhatinternet/markflat-editor)
[![PHP Tests](https://github.com/auvernhatinternet/markflat-editor/actions/workflows/php.yml/badge.svg)](https://github.com/auvernhatinternet/markflat-editor/actions/workflows/php.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

# MarkFlat Editor Bundle

A Symfony bundle for editing Markdown files with a secure administration interface.

## Installation

1. Install the bundle via Composer :
```bash
composer require markflat/markflat-editor
```

2. Add the bundle to your `config/bundles.php` :
```php
return [
    // ...
    MarkFlatEditor\MarkFlatEditorBundle::class => ['all' => true],
];
```

3. Add the following configuration to your `.env` file :
```
ADMIN_PASSWORD=your_admin_password
```

## Usage

Access the administration interface via URL : `/admin?password=your_admin_password`

# MarkFlat Editor Bundle

Un bundle Symfony pour éditer des fichiers Markdown avec une interface d'administration sécurisée.

## Installation

1. Installez le bundle via Composer :
```bash
composer require markflat/markflat-editor
```

2. Ajoutez le bundle dans votre `config/bundles.php` :
```php
return [
    // ...
    MarkFlatEditor\MarkFlatEditorBundle::class => ['all' => true],
];
```

3. Ajoutez la configuration suivante dans votre fichier `.env` :
```
ADMIN_PASSWORD=votre_mot_de_passe_admin
```

## Utilisation

Accédez à l'interface d'administration via l'URL : `/admin?password=votre_mot_de_passe_admin`

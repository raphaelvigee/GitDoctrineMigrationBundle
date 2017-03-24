# GitDoctrineMigrationBundle

GitDoctrineMigrationBundle is a helper for managing doctrine migrations and git branches.

## Installation
Add the dependency:

```
composer require raphaelvigee/git-doctrine-migration-bundle dev-master
```

Register in `AppKernel.php`:
```php
$bundles = [
    ...
    new RaphaelVigee\GitDoctrineMigrationBundle\RaphaelVigeeGitDoctrineMigrationBundle(),
    ...
];
```

## Usage:

### Prepare checkout:
```
bin/console doctrine:migrations:prepare-checkout <target>
```

_The \<target> should be a branch name (example: `master`)_

An SQL dump is created under `var/git-doctrine-migration`

### Restore:

```
bin/console doctrine:migrations:restore
```

Restores the SQL dump corresponding to the current commit

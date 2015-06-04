# Permission Controller
This library adds extended permission support to controllers in OctoberCMS. It is built on top of the default `$requiredPermissions` property but extends it to be more powerful by adding the possibilty to define different permissions for different actions.

> **Hint:** Due to the way Controller Behaviors work this is a class (and not a Behavior) you need to extend your controllers from.

### Example
This will check for the required permissions depending on the action.
```php
<?php namespace Vendor\Plugin\Controllers;

use Bock\PermissionController\Controller;

/**
 *  Back-end Controller
 */
class MyController extends Controller
{
    public $requiredPermissions = [
        'index'     => 'vendor.plugin.controller.list',
        'delete'	=> 'vendor.plugin.controller.delete',
        'create'	=> 'vendor.plugin.controller.create',
        'update'	=> 'vendor.plugin.controller.update',
    ];

    // Rest of the code
}
```

### Installation
First, you'll need to add the package to your `composer.json` and run `composer update`.

```json
{
    "require": {
        "niclasleonbock/permissioncontroller": "dev-master"
    },
}
```

### Helpers
##### getRequiredPermission
```php
public getRequiredPermission($action)
```

Gets the required permission for an action (e.g. `delete`).


##### isAllowed
```php
public isAllowed($permission)
```

Alias for `$this->user->hasAccess`. Checks if the authenticated user has a given permission.


##### can
```php
public can($action)
```

Checks wether the action is allowed in this request cycle, based on the user permissions. Can also be helpful in templates, e.g. to hide action buttons.


##### notAllowed
```php
public notAllowed($flash = false)
```

Ends the current request with a not-allowed message.
Either as flash message (if `$flash` is set to `true`) or an error page.


### Bugs
Create a pull request or issue.

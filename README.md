Buggyman module for Zend Framework 2
====================================


INSTALL
-------

The recommended way to install is through composer.

```json
{
    "require": {
        "buggymanhq/buggyman-module": "1.*"
    }
}
```

USAGE
-----

Add `BuggymanModule` to your `config/application.config.php` to enable module.

buggyman.global.php
-------------------

```php
<?php

return array(
    'buggyman' => array(
        // is enabled
        'enabled' => true,
        // api token
        'token' => 'HERE_PASTE_YOUR_TOKEN'
    )
);
```

buggyman.local.php
------------------

```php
<?php

return array(
    'buggyman' => array(
        'enabled' => false,
    )
);
```
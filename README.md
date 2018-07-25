# OST Simple Token - KIT API - PHP Wrapper

This is an unofficial OST Simple Token PHP Wrapper and just supported by the community. In comparison to the official API, it supports intelli sense for most PHP IDEs while coding.

# Usage
```php
$settingMode = 'test'; // or 'live' (does not work yet)

$userRepo = new UserRepository($settingMode, $settingApiKey, $settingApiSecret, $settingCompanyUuid);

/** @var UserModel[] $userList */
$userList = $userRepo->list(); // see parameters for available filter
```

# Missing & ToDo
- Parse the "hasNextPage" property into a meta data object (for pagination; waiting for OST to add more information into the object)
- Wrap OST errors into custom error classes
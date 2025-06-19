# Admin Documentation

## Overview

The `Admin` model represents an admin user in the Laravel project. It extends the `User` model and includes a `boot` method that adds a global scope to filter admin users based on the `user_type` attribute.

## boot Method

The `boot` method is a static method that is called when the model is booted. It adds a global scope to the query builder to filter admin users based on the `user_type` attribute. The global scope ensures that only records with the `TYPE_ADMIN` user type are retrieved.

### Global Scope

The global scope is defined using a closure that modifies the query builder. It adds a `where` clause to the query to filter records where the `user_type` attribute is equal to `User::TYPE_ADMIN`.

```php
static::addGlobalScope(function ($query) {
    $query->where('user_type', User::TYPE_ADMIN);
});
```

This global scope is automatically applied to all queries for the `Admin` model, ensuring that only admin users are retrieved.
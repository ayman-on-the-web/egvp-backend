# Volunteer Documentation

## Overview

The `Volunteer` model represents a volunteer in the Laravel project. It extends the `User` model and includes a `casts` method, a `boot` method, and a global scope.

## casts Method

The `casts` method defines the attributes that should be cast to specific types. It returns an array with the following key-value pairs:

- `email_verified_at`: Cast to a `datetime` type.
- `password`: Cast to a `hashed` type.
- `is_active`: Cast to a `boolean` type.
- `is_approved`: Cast to a `boolean` type.
- `active_until`: Cast to a `date` type.
- `approved_at`: Cast to a `datetime` type.
- `points`: Cast to a `float` type.

## boot Method

The `boot` method is a static method that is called when the model is booted. It adds a global scope to the query builder to filter volunteer users based on the `user_type` attribute. The global scope ensures that only records with the `TYPE_VOLUNTEER` user type are retrieved.

### Global Scope

The global scope is defined using a closure that modifies the query builder. It adds a `where` clause to the query to filter records where the `user_type` attribute is equal to `User::TYPE_VOLUNTEER`.

```php
static::addGlobalScope(function ($query) {
    $query->where('user_type', User::TYPE_VOLUNTEER);
});
```

This global scope is automatically applied to all queries for the `Volunteer` model, ensuring that only volunteer users are retrieved.
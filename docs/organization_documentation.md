# Organization Documentation

## Overview

The `Organization` model represents an organization in the Laravel project. It extends the `User` model and includes specific attributes, relationships, and methods for organizations.

## Attributes

The `Organization` model has the following attributes:

- `email_verified_at`: The timestamp when the email was verified.
- `password`: The hashed password of the organization.
- `is_active`: A boolean indicating if the organization is active.
- `is_approved`: A boolean indicating if the organization is approved.
- `active_until`: The date until the organization is active.
- `approved_at`: The date when the organization was approved.
- `points`: The points associated with the organization.

## casts Method

The `casts` method defines the attributes that should be cast to specific types. It returns an array with the following key-value pairs:

- `email_verified_at`: Cast to a `datetime` type.
- `password`: Cast to a `hashed` type.
- `is_active`: Cast to a `boolean` type.
- `is_approved`: Cast to a `boolean` type.
- `active_until`: Cast to a `date` type.
- `approved_at`: Cast to a `date` type.
- `points`: Cast to a `float` type.

## boot Method

The `boot` method is a static method that is called when the model is booted. It adds a global scope to the query builder to filter organizations based on the `user_type` attribute. The global scope ensures that only records with the `TYPE_ORGANIZATION` user type are retrieved.

## events Relationship

The `events` method defines a relationship between the `Organization` model and the `Event` model. It returns a `hasMany` relationship, indicating that an organization can have many events. The relationship is defined using the `hasMany` method and the `Event` model class.
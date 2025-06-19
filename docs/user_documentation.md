# User Documentation

## Overview

The `User` model represents a user in the Laravel project. It includes constants for different user types and identification types, fillable attributes, hidden attributes, casts, and methods for JWT authentication.

## Constants

The `User` model includes the following constants for different user types:

- `TYPE_ADMIN`: "Admin"
- `TYPE_ORGANIZATION`: "Organiztion"
- `TYPE_VOLUNTEER`: "Volunteer"

The `User` model also includes the following constants for different identification types:

- `IDENTIFICATION_NATIONAL_ID`: "National ID"
- `IDENTIFICATION_COMMERCIAL`: "Commercial Registeration Number"

## Fillable Attributes

The `User` model has the following fillable attributes:

- `name`: The name of the user.
- `email`: The email address of the user.
- `password`: The password of the user.
- `phone`: The phone number of the user.
- `address`: The address of the user.
- `profile_photo_base64`: The base64 encoded profile photo of the user.
- `identification_type`: The type of identification of the user.
- `identification_number`: The identification number of the user.
- `user_type`: The type of the user.
- `is_active`: A boolean indicating if the user is active.
- `active_until`: The date until the user is active.
- `is_approved`: A boolean indicating if the user is approved.
- `approved_at`: The date when the user was approved.
- `points`: The points associated with the user.
- `skills`: The skills of the user.
- `details`: Additional details of the user.

## Hidden Attributes

The `User` model has the following hidden attributes:

- `password`: The password of the user.
- `remember_token`: The remember token of the user.

## Casts Method

The `casts` method defines the attributes that should be cast to specific types. It returns an array with the following key-value pairs:

- `email_verified_at`: Cast to a `datetime` type.
- `password`: Cast to a `hashed` type.
- `is_active`: Cast to a `boolean` type.
- `is_approved`: Cast to a `boolean` type.
- `active_until`: Cast to a `date` type.
- `approved_at`: Cast to a `date` type.
- `points`: Cast to a `float` type.

## JWT Authentication Methods

The `User` model includes the following methods for JWT authentication:

### getJWTIdentifier

- **Description**: Returns the identifier that will be stored in the subject claim of the JWT.
- **Return Type**: `mixed`

### getJWTCustomClaims

- **Description**: Returns a key-value array containing any custom claims to be added to the JWT.
- **Return Type**: `array`
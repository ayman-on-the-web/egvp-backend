# Application Documentation

## Overview

The `Application` model represents an application in the Laravel project. It includes constants for different statuses, fillable attributes, casts, relationships, and methods for making decisions.

## Constants

The `Application` model includes the following constants for different statuses:

- `STATUS_PENDING`: 'Pending'
- `STATUS_REJECTED`: 'Rejected'
- `STATUS_APPROVED`: 'Approved'

## Fillable Attributes

The `Application` model has the following fillable attributes:

- `volunteer_id`: The ID of the associated volunteer.
- `event_id`: The ID of the associated event.
- `is_approved`: A boolean indicating if the application is approved.
- `status`: The status of the application.
- `decision_at`: The timestamp when the decision was made.

## Casts

The `Application` model has the following casts:

- `is_approved`: Cast to a `boolean` type.

## Relationships

The `Application` model has the following relationships:

- `volunteer()`: Belongs to a `Volunteer` model.
- `event()`: Belongs to an `Event` model.

## Methods

The `Application` model includes the following methods:

### make_decision

- **Description**: Updates the application's status, decision timestamp, and approval based on the given decision.
- **Parameters**: `$decision` - The decision to make (Pending, Rejected, Approved).
- **Return Type**: `bool`

### approve

- **Description**: Approves the application by calling `make_decision` with the `STATUS_APPROVED` constant.
- **Return Type**: `bool`

### reject

- **Description**: Rejects the application by calling `make_decision` with the `STATUS_REJECTED` constant.
- **Return Type**: `bool`
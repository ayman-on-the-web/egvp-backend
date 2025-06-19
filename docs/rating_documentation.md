# Rating Documentation

## Overview

The `Rating` model represents a rating in the Laravel project. It includes fillable attributes and relationships with the `Volunteer` and `Event` models.

## Fillable Attributes

The `Rating` model has the following fillable attributes:

- `volunteer_id`: The ID of the associated volunteer.
- `event_id`: The ID of the associated event.
- `rate`: The rating value.

## Relationships

The `Rating` model has the following relationships:

- `volunteer()`: Belongs to a `Volunteer` model.
- `event()`: Belongs to an `Event` model.
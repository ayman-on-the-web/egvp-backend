# Attendance Documentation

## Overview

The `Attendance` model represents an attendance record in the Laravel project. It includes fillable attributes and relationships with the `Event` and `Volunteer` models.

## Fillable Attributes

The `Attendance` model has the following fillable attributes:

- `event_id`: The ID of the associated event.
- `volunteer_id`: The ID of the associated volunteer.
- `hours`: The number of hours attended.

## Relationships

The `Attendance` model has the following relationships:

- `event()`: Belongs to an `Event` model.
- `volunteer()`: Belongs to a `Volunteer` model.
# EventCategory Documentation

## Overview

The `EventCategory` model represents an event category in the Laravel project. It includes a fillable attribute and a relationship with the `Event` model.

## Fillable Attribute

The `EventCategory` model has the following fillable attribute:

- `name`: The name of the event category.

## Relationship

The `EventCategory` model has the following relationship:

- `events()`: Has many `Event` models.
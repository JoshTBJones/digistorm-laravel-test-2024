# Issues

I have addressed the majority of issues in the code review. However, some of the following issues were caused by new work and not applicable to the initial implementation.

- Contact and PhoneNumber should use cascade on delete event allowing us to remove the manual boot::deleting method. Letting the database handle this would be much more performant on larger datasets.
    - assuming we're working on an existing project, it is not a simple task to change the relationships. We would need to export the data, drop the columns, update the relationships, run the updated migration, and finally import the data.
- `app.blade.php` should be updated to use `@vite` instead of `@script` and `@style`

## Search
- Current implementation **is not** effective for large datasets.
- Current implementation uses `LIKE` for searching.
- Indexes have been added to the `contacts` table, for the `first_name`, `last_name`, and `company_name` columns.
- Fuzzy search was decided to be more important in the initial implementation. However, using full text search does not allow for this.
- Future implementation should use full text search with indexes, and Laravel Scout or similar.

## Phone Number Input
- The functionality of adding phone numbers was planned to be implemented as a single reusable component.
- Due to time constraints, this hasn't been fully implemented, an almost complete version can be seen in `components/form/phone-number.blade.php`.

## Buttons
- Planned to create a reusable button component, but due to time constraints, this hasn't been fully implemented.
- Component would also handle button groups
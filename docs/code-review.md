# Code Review

## Contact Model

`models/Contact.php`

- `$guarded` property is unprotected
    - Using an empty guarded array allows for all attributes to be mass-assigned, which can cause vulnerabilities if not properly validated.
    - Best practice is to use the `fillable` property instead, and explicitly define which attributes should be mass-assignable.
- `getFullNameAttribute` Potential for SQL injection
    - Concatenating strings without proper handling could lead to unexpected whitespace issues or even XSS vulnerabilities if user-provided data isn’t sanitized elsewhere.
    - Use `trim()`to sanitize the data before concatenation.
    - Suggested solution:
    ```
        public function getFullNameAttribute()
        {
            return e(trim($this->first_name . ' ' . $this->last_name));
        }
    ```

## Migrations

`2020_10_17_111008_create_contacts_table`

- `email` column
    - Not Unique: Emails should always be unique to prevent duplicate entries unless there is a specific use case for allowing duplicates
    - Is Nullable: This is a good practice to allow for flexibility in data entry, but it should be properly validated to ensure that the email is a valid format.
    - Consider adding an index to the column to improve query performance.
- `first_name` & `last_name` columns
    - Consider increasing the size of the columns to 100 characters to allow for more flexibility in data entry.
- `DOB` column
    - Consider renaming to `date_of_birth` to better describe the data it contains, and stick to the naming convention of `snake_case` for column names.
- Architecture
    - Splitting the `contacts` table into two separate tables: `people` and `companies` would be a better approach for organizing the data.
    - This would allow for better organization of the data, and would make it easier to query the data.
    - This would also allow for better scalability, as the data would be more organized and easier to query.

`2020_10_17_112328_create_phone_numbers_table`

- `number` column
    - Consider increasing the size of the column to 15 characters to allow for more flexibility in data entry.
    - As spec requires numbers to be unique, consider adding a unique index to the column, this will prevent duplicate entries at the database level.
- `contact_id` column
    - Consider adding an index to the column to improve query performance.
    - As we are using a foreign key, consider adding a `cascade` on delete to the column to ensure that when a contact is deleted, all associated phone numbers are also deleted.
- Architecture
    - Consider adding a `phone_number_type` column to the table to allow for more flexibility in data entry.

`ContactController.php`
- No validation for user input. When accepting any user submitted data, we should always validate the data to ensure it is safe and valid.
- No error handling. We should always handle errors and provide feedback to the user.
- Store and Update methods return a view, but should return a redirect response.
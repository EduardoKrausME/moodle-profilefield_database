# Plugin User Field DB

This plugin allows Moodle administrators to create user profile fields filled with data from a database table. By using this plugin, administrators can create data tables that students can select from and automatically generate reports in Kopere BI.

## Features
- Create user profile fields linked to database tables.
- Define categories of data stored in the database.
- Allow users to select predefined data when editing their profile.
- Facilitate data consistency and minimize entry errors by using database-based options.

## Setup
1. Go to **Site Administration > Users > User profile fields**.
2. Create a new profile field of type "User Field DB".
3. Configure the field by specifying:
   - Database table from which data will be extracted.
   - Key and value columns to display options.
   - Additional conditions to filter the data (optional).
4. Save the field and check if it appears in the user profile form.

## Usage
- Users will see a dropdown list when editing their profile and will see the data previously set by the administrator.
- Administrators can update the data in the database table to dynamically modify the available options.

## Requirements
- Moodle 4.1 or higher.
- Access to configure custom profile fields.

## Use Case Examples
- **Cost Center**: Allows users to select their cost center from a pre-configured list in the database.
- **Department Selection**: Allows users to select their department from a pre-configured list in the database.
- **Branches**: Use a database table to maintain a list of branches by region for users to choose from.
- **Custom Options**: Any scenario where a dynamic list of options is needed in user profiles.

## Support
For issues, feature requests, or contributions, open an issue or submit a pull request in this repository.

## License
This plugin is licensed under the [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html).

**Developed by [Eduardo Kraus](https://eduardokraus.com/)**

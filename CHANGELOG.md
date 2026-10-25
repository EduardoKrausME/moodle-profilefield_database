# Changelog

This changelog covers only the two most recent published releases of `profilefield_database`, based on their published Git tags and the changes since the preceding release tags. The `dependencies` commit made on October 20, 2026, after release `1.1.16`, is intentionally excluded.

## [1.1.16] - 2026-10-15

### Changed
- Updated the plugin icon asset at `pix/icon.png`.

### CI maintenance
- Updated the PostgreSQL CI service image from PostgreSQL 16 to PostgreSQL 17.
- Updated the MariaDB CI service from version 10 to 11.4, adjusting container environment variables and using the MariaDB `healthcheck.sh --connect --innodb_initialized` health check.
- Updated release version metadata to `1.1.16`.

**Full comparison:** [1.1.15...1.1.16](https://github.com/EduardoKrausME/moodle-profilefield_database/compare/1.1.15...1.1.16)

## [1.1.15] - 2026-10-12

### CI compatibility
- Updated the PHP 8.3 test-matrix entry from Moodle 5.2 to Moodle 5.3.
- Added explicit CI checkouts of Kopere Dashboard and Kopere BI and configured `moodle-plugin-ci` to install them as additional plugins for dependency-aware testing.

### Release security
- Changed the automatic release workflow to pass the commit message through the `RELEASE_MESSAGE` environment variable rather than interpolating commit text directly into a shell script, reducing shell-injection risk.
- Updated release version metadata to `1.1.15`.

**Full comparison:** [1.1.14...1.1.15](https://github.com/EduardoKrausME/moodle-profilefield_database/compare/1.1.14...1.1.15)

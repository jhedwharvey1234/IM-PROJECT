# Deployment Notes for IM Project

## Required production changes

- Set `CI_ENVIRONMENT = production` in `.env` for live deployment.
- Configure `app.baseURL` to the production domain if auto-detection is not sufficient.
- Enable `app.forceGlobalSecureRequests` and `app.CSPEnabled` as needed.
- Ensure `database.default.*` values use secure production credentials.
- Set a real `encryption.key` in `.env` or use a secure key store.
- Configure session settings to use a secure save path if required.
- Disable debug and debugbar in production.

## Secrets and sensitive configuration

- Do not commit `ENTRA_CLIENT_SECRET`, `ENTRA_CLIENT_ID`, or tenant IDs to source control.
- Use server-level environment variables for Entra and other credentials.
- If Entra auth is not required in production, set `ENTRA_ENABLED = false`.

## Webserver requirements

- Document root must point to the `public/` directory.
- Verify `.htaccess` or web server rewrite rules are configured correctly.
- Ensure `writable/`, `writable/cache/`, `writable/logs/`, and `writable/session/` are writable by the web server.

## Dependency and runtime requirements

- PHP 8.1+ is required (PHP 8.2 is already available in local environment).
- Required PHP extensions: `intl`, `mbstring`, `zip`, `openssl`, `json`, `fileinfo`.
- Composer dependencies are installed; run `composer install` on the deployment server.

## Testing and validation

- Run PHPUnit tests to validate the application before deployment.
- Confirm the database schema is current and migrations are executed.
- Verify the app works under production settings with the configured base URL.

## Notes

- Composer has been installed and dependencies resolved in the local project.
- The `.env` file now includes deployment guidance comments for production.

# AlkeLink Final Enterprise Demo

AlkeLink is a PHP MVC healthcare inventory intelligence demo tailored to Mozambican hospital operations.

## Included enterprise modules
- Secure login and role-based access
- Executive dashboard
- Inventory catalog
- Stock operations workspace
- Alerts center
- Procurement intelligence
- Predictive forecasting
- AI copilot
- Barcode scan station with camera + handheld workflow
- Offline sync center
- Facilities visibility
- Reports
- Audit trail
- Users, roles, and settings admin pages

## Demo logins
- admin@alkelink.org / password123
- pharmacist@alkelink.org / password123
- procurement@alkelink.org / password123
- admin.hospital@alkelink.org / password123
- auditor@alkelink.org / password123

## Run locally
1. Put the folder in your web root or use PHP built-in server.
2. Open `/public` or root `index.php`.
3. Import `database/alkelink.sql` if you want a DB shell. The app also works in demo mode when DB is unavailable.

## Notes
This version is optimized for demos and investor presentations while keeping a real MVC structure that can be extended into production CRUD and analytics services.

Patch note: asset paths were corrected so the app works when opened through the project root `index.php` on localhost.

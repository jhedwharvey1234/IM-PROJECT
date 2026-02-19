# Document Alerts System - Implementation Summary

## Overview
A complete alert scheduling system has been added to the Document Management module, allowing users to set date/time reminders for documents with optional descriptions and a calendar view.

## Database Schema

### New Table: document_alerts
```sql
CREATE TABLE document_alerts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_id INT UNSIGNED NOT NULL,
    alert_date DATE NOT NULL,
    alert_time TIME NULL,
    description TEXT NULL,
    is_notified TINYINT(1) DEFAULT 0,
    created_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_document_alerts_document FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE ON UPDATE CASCADE
);
```

**Fields:**
- `id`: Unique identifier
- `document_id`: Foreign key to documents table
- `alert_date`: Date when alert should trigger (required)
- `alert_time`: Optional time for alert (allows all-day alerts if NULL)
- `description`: Optional alert description/notes
- `is_notified`: Flag to track if alert has been processed
- `created_by`: User ID who created the alert
- `created_at`/`updated_at`: Timestamps

## Backend Implementation

### Model: DocumentAlert (app/Models/DocumentAlert.php)
- Automatically handles timestamps and model validation
- **Methods:**
  - `getAlertsByDocument($documentId)`: Get all alerts for a document
  - `getUpcomingAlerts($days = 7)`: Get alerts in the next N days
  - `getAlertsForMonth($year, $month)`: Get all alerts for a calendar month
  - `markAsNotified($id)`: Mark alert as processed

### Controller: DocumentController (app/Controllers/DocumentController.php)
**New Methods:**
- `addAlert($documentId)`: Create new alert with POST validation
- `deleteAlert($documentId, $id)`: Delete specific alert with authorization check
- `alerts()`: Display calendar view with month navigation

**Updated Methods:**
- `details($id)`: Now includes alerts in the document details view

### Routes (app/Config/Routes.php)
```
POST  /documents/alert/store/:id        → DocumentController::addAlert
GET   /documents/alert/delete/:id/:id   → DocumentController::deleteAlert
GET   /documents/alerts                 → DocumentController::alerts
```

## Frontend Implementation

### Alert Modal Form (documents/details.php)
- **Bootstrap modal** triggered by "Add Alert" button
- **Form Fields:**
  - Alert Date (required date input)
  - Alert Time (optional time input - allows all-day alerts)
  - Description (optional textarea up to 5000 chars)
- **Location:** Bottom of document details page

### Alert Table (documents/details.php)
- Displays all alerts for the current document
- **Columns:**
  - Date (formatted as "Mon DD, YYYY")
  - Time (shows "All day" if no time set)
  - Description (truncated to 50 chars with tooltip)
  - Delete action button

### Calendar View (documents/alerts.php)
**Calendar Features:**
- Month/year navigation with previous/next/today buttons
- 7x6 calendar grid
- Color-coded cells:
  - Blue: Today's date
  - Light blue: Dates with alerts
  - Alert badges show document title (truncated to 15 chars)
- Hover tooltip shows full description
- Professional Bootstrap styling

**Status Indicators:**
- Yellow badge: Pending notification
- Green badge: Notified/Done

### Upcoming Alerts Section (documents/alerts.php)
- 30-day lookahead list
- **Displays:**
  - Document title (clickable link to details)
  - Alert date/time formatted
  - Description preview (50 char truncation)
  - Pending/Done status badge
- Auto-scrollable container for long lists

### Sidebar Navigation
New link in header.php:
- Icon: Calendar event (<i class="bi bi-calendar-event"></i>)
- Text: "Document Alerts"
- Links to `/documents/alerts` calendar view

## Features

### Alert Creation
✓ Modal form on document details page
✓ Date picker with validation
✓ Optional time picker for specific alerts
✓ Optional description/notes field
✓ Automatic user tracking (created_by)
✓ Flash message feedback

### Alert Management
✓ View all alerts for each document
✓ Delete individual alerts with confirmation
✓ Cascade delete when document is removed
✓ Notified flag for tracking processed alerts

### Calendar View
✓ Month navigation with proper year handling
✓ "Today" quick navigation button
✓ Multiple alerts per date display
✓ Document title preview with truncation
✓ Upcoming alerts sidebar (30-day lookahead)
✓ Clickable alerts link to document details
✓ Responsive Bootstrap 5.3 design

## Setup Instructions

### 1. Create Database Table
Run one of these:

**Option A - SQL Script:**
```bash
# Execute in MySQL directly
mysql -u root inventory_management < create_document_alerts_table.sql
```

**Option B - PHP Setup Script:**
```bash
php setup_document_alerts.php
```

**Option C - CodeIgniter Migration:**
```bash
php spark migrate
```

### 2. Verify Installation
- Navigate to a document details page
- Confirm "Add Alert" button appears in Alerts section
- Click button to open modal
- Try adding an alert
- Verify entry appears in Alerts table
- Navigate to "Document Alerts" in sidebar
- Confirm calendar displays properly

## File Locations

### Database
- Migration: `app/Database/Migrations/2026-02-18-000001_CreateDocumentAlertsTable.php`
- SQL Script: `create_document_alerts_table.sql`
- Setup: `setup_document_alerts.php`

### Backend
- Model: `app/Models/DocumentAlert.php`
- Controller: `app/Controllers/DocumentController.php` (updated)
- Routes: `app/Config/Routes.php` (updated)

### Frontend
- Details View: `app/Views/documents/details.php` (updated - added alerts section + modal)
- Alerts Calendar: `app/Views/documents/alerts.php` (new)
- Header: `app/Views/partials/header.php` (updated - added alerts link)

## Data Validation

### Alert Creation
- `alert_date` (required): Valid date format validation
- `alert_time` (optional): HH:MM format with regex validation
- `description` (optional): Max 5000 characters
- `document_id`: Must exist in documents table
- `created_by`: Auto-populated from session

### Delete Authorization
- Superadmin-only access (via ensureSuperadmin check)
- Document ownership verification
- Flash message feedback on success/failure

## Error Handling

- ✓ No SQL errors on all modified files
- ✓ Foreign key constraints properly configured
- ✓ Cascade delete on document removal
- ✓ User feedback via flash messages
- ✓ Modal form validation on submit
- ✓ Date/time format validation in controller

## Usage Examples

### Add Alert via UI
1. Open any document details page
2. Click "Add Alert" button in Alerts section
3. Fill modal form:
   - Select alert date
   - (Optional) Set specific time
   - (Optional) Enter description
4. Click "Create Alert"
5. Alert appears in Alerts table

### View Calendar
1. Click "Document Alerts" in sidebar
2. Navigate months with prev/next arrows
3. Click "Today" to jump to current month
4. Hover over alert badges for full description
5. Click alert/document title to go to details page

### Manage Alerts
- Delete: Click trash icon in Alerts table
- Edit: Delete and recreate with new data
- Track: Upcoming alerts show in 30-day preview

## Security Notes

- ✓ All views escaped with esc() to prevent XSI
- ✓ CSRF tokens on all forms
- ✓ Superadmin-only access enforced
- ✓ Foreign key constraints prevent orphaned records
- ✓ User ID auto-populated from session (not form input)
- ✓ SQL injection protection via CodeIgniter's parameterized queries

## Future Enhancements (Optional)

- Email notification on alert date
- Recurring alerts
- Alert categories/colors
- Export alerts to ICS/calendar file
- Alert snoozed/dismiss options
- Team-based alert assignments
- Slack/Teams integration

---

**Status:** ✓ Complete and tested
**Date Created:** February 18, 2026
**Framework:** CodeIgniter 4
**Database:** MySQL

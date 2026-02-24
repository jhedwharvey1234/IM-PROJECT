# Multi-Page DCF Form Implementation

## Overview
The DCF public form has been redesigned to use a multi-page layout, making it more user-friendly and easier to complete.

## New Features

### 1. **Multi-Page Layout**
- **Page 1**: User Information & Consent
  - Name (required)
  - Email (required)
  - Mobile (optional)
  - Terms & Conditions with scrollable text box
  - Consent checkbox (required to proceed)

- **Pages 2+**: Each Part on its Own Page
  - Only questions from that part are shown
  - Part title and description displayed
  - Clean, focused question view

### 2. **Navigation Controls**
- **Page 1 Button**: "Start Answering" button
- **Middle Pages**: "Back" and "Next" buttons
- **Last Page**: "Back" and "Submit" buttons
- All navigation prevents leaving a page with invalid/required fields uncompleted

### 3. **Progress Indicator**
- Shows current page and total pages (e.g., "3 / 5")
- Visual progress bar that fills as user advances
- Located at top of form for easy reference

### 4. **Consent Tracking**
- User must explicitly agree to terms before starting
- `user_consent` field stored in database (0 or 1)
- `consent_timestamp` recorded when consent given
- Both fields available for compliance/audit purposes

### 5. **Form Validation**
- Page-level validation before allowing navigation
- All required fields on current page must be completed
- Clear error messages if validation fails
- Rate Me questions validated within min/max range

## Database Changes

### SQL Script to Apply
Run `setup_dcf_consent.php` to add the schema changes:

```sql
ALTER TABLE dcf_responses ADD COLUMN user_consent TINYINT(1) DEFAULT 0 AFTER respondent_email;
ALTER TABLE dcf_responses ADD COLUMN consent_timestamp DATETIME NULL AFTER user_consent;
CREATE INDEX idx_dcf_responses_user_consent ON dcf_responses(user_consent);
```

**Or Execute Manually:**
```bash
mysql -u your_user -p your_database < add_dcf_consent_field.sql
```

## File Changes

### Modified Files:
1. **public/Views/dcf/public_form.php** - Complete redesign for multi-page layout
2. **app/Controllers/DcfPublicController.php** - Updated submit() to handle consent
3. **app/Models/DcfResponse.php** - Added new fields to allowedFields
4. **app/Config/Routes.php** - Added routes (no changes needed)

### New Files:
1. **add_dcf_consent_field.sql** - SQL script for schema update
2. **setup_dcf_consent.php** - PHP setup script to apply schema changes

## How to Setup

### Step 1: Apply Database Schema
Option A (Recommended):
```bash
Navigate to: http://localhost/IM/setup_dcf_consent.php
```

Option B (Manual):
```bash
mysql -u root -p your_database < add_dcf_consent_field.sql
```

### Step 2: Test the Form
1. Create or edit a DCF with multiple parts
2. Get the share token or public form link
3. Access the form at: `http://localhost/IM/dcf/form/[share_token]`
4. Try navigating through pages

## User Experience Flow

```
Page 1: User Info
  ├─ Fill Name, Email, Mobile
  ├─ Read Terms & Conditions
  └─ Check "I Agree" → Click "Start Answering"
    
    ↓
    
Page 2: First Part
  ├─ Answer all questions in Part 1
  └─ Click "Next" or "Back"
    
    ↓
    
Page 3+: Subsequent Parts
  ├─ Answer all questions
  └─ Click "Next" or "Back"
    
    ↓
    
Last Page: Final Part
  ├─ Answer all questions
  ├─ Click "Back" to review or "Submit"
  └─ On Submit → Success message
```

## Technical Details

### Form Field Names
- `respondent_name` - User's name
- `respondent_email` - User's email
- `respondent_mobile` - User's phone
- `user_consent` - Checkbox value (1 if checked)
- `answers[question_id]` - Answer for each question

### JavaScript Features
- `showPage(pageNum)` - Display specific page
- `validateCurrentPage()` - Validate page before advance
- `updateRateButtons(gridId)` - Visual feedback for rating
- Progress bar auto-updates
- Smooth scroll to top on page change

### Answer Type Layouts Preserved
- Multiple Choice: Vertical radio buttons
- Checkbox: Grid layout
- Dropdown: Standard select
- Rate Me: Interactive button grid (1-10 or custom)
- Short Answer: Text input
- Paragraph: Textarea

## Browser Compatibility
- Chrome/Edge: ✓ Fully supported
- Firefox: ✓ Fully supported
- Safari: ✓ Fully supported
- IE11: ✓ Basic support

## Future Enhancements (Optional)
- Progress save/resume (session-based)
- Mobile-optimized view improvements
- Accessibility enhancements (ARIA labels)
- Analytics dashboard for consent tracking
- Automated email on form submission

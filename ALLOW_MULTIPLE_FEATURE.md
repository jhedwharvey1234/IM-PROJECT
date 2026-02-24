# Allow Multiple Answers Feature

## Overview
The **Allow Multiple Answers** toggle enables flexible control over whether respondents can select one or multiple answers for questions with options (multiple choice, checkbox, dropdown, and advance checkbox).

## Database Migration

### Option 1: Run SQL directly
Execute the SQL file in your database:
```sql
-- File: add_allow_multiple_to_dcf_questions.sql
ALTER TABLE dcf_questions 
ADD COLUMN allow_multiple TINYINT(1) NOT NULL DEFAULT 0 
AFTER answer_type;
```

### Option 2: Run PHP migration script
Access the migration script in your browser:
```
http://localhost/IM/add_allow_multiple_field.php
```

## Usage

### In DCF Create/Edit Forms
1. Add a question with Multiple Choice, Checkbox, Dropdown, or Advance Checkbox type
2. Toggle **"Allow Multiple Answers"** switch appears below the Answer Type selector
3. Enable the toggle to allow multiple selections
4. Disable the toggle to restrict to single selection only

### Default Behaviors

| Question Type | Default | When Toggle ON | When Toggle OFF |
|--------------|---------|----------------|-----------------|
| Multiple Choice | Single (Radio) | Multiple (Checkboxes) | Single (Radio) |
| Checkbox | Multiple | Multiple | Single (Radio-like) |
| Dropdown | Single | Multi-select dropdown | Single dropdown |
| Advance Checkbox | Multiple | Multiple per row | Single per row |

## Technical Details

### Database Field
- **Field Name**: `allow_multiple`
- **Type**: `TINYINT(1)`
- **Default**: `0` (false)
- **Location**: After `answer_type` column in `dcf_questions` table

### Model Update
Updated `DcfQuestion.php` model to include `allow_multiple` in `$allowedFields`.

### Form Changes
- **create.php**: Added toggle switch with icon and helper text
- **edit.php**: Added toggle switch with icon and helper text
- **toggleOptionsArea()**: Updated to show/hide toggle based on question type

### UI Components
```html
<div class="allow-multiple-area mt-2">
    <div class="form-check form-switch">
        <input class="form-check-input allow-multiple-toggle" 
               type="checkbox" 
               name="parts[X][questions][Y][allow_multiple]" 
               value="1">
        <label class="form-check-label">
            <i class="bi bi-check2-square"></i> Allow Multiple Answers
        </label>
    </div>
    <small class="text-muted">
        Enable to allow respondents to select multiple options.
    </small>
</div>
```

## Benefits

1. **Flexibility**: Convert any option-based question between single and multiple selection
2. **User Control**: Form creators decide the selection behavior
3. **Consistency**: Unified toggle interface across all applicable question types
4. **Visual Feedback**: Bootstrap switch with icon provides clear state indication

## Examples

### Example 1: Multiple Choice → Multiple Selection
**Before**: "What is your favorite color?" (radio buttons, single choice)
**After Toggle ON**: "What are your favorite colors?" (checkboxes, multiple choice)

### Example 2: Dropdown → Multi-select
**Before**: "Select your department" (single dropdown)
**After Toggle ON**: "Select all departments you work with" (multi-select dropdown)

### Example 3: Advance Checkbox → Single per Row
**Before**: Grid allowing multiple columns per row
**After Toggle OFF**: Grid restricting to one column per row

## Future Enhancements
- Public form rendering will need to respect the `allow_multiple` flag
- Controller validation should handle both single and multiple answer formats
- Analytics should differentiate between single and multiple answer questions

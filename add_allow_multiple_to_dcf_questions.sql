-- Add allow_multiple field to dcf_questions table
-- This field controls whether questions can have multiple answers selected

ALTER TABLE dcf_questions 
ADD COLUMN allow_multiple TINYINT(1) NOT NULL DEFAULT 0 
AFTER answer_type;

-- Default behavior:
-- multiple_choice: allow_multiple = 0 (single selection, radio)
-- checkbox: allow_multiple = 1 (multiple selection)
-- dropdown: allow_multiple = 0 (single selection)
-- advance_checkbox: allow_multiple = 1 (multiple selection in grid)

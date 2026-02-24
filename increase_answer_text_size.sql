-- Increase answer_text field size to support larger WYSIWYG content with images
-- TEXT (65,535 bytes) → MEDIUMTEXT (16,777,215 bytes / ~16MB)

ALTER TABLE dcf_response_answers 
MODIFY COLUMN answer_text MEDIUMTEXT NULL;

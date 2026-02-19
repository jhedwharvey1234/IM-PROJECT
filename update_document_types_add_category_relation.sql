ALTER TABLE document_types
ADD COLUMN IF NOT EXISTS document_category_id INT UNSIGNED NULL AFTER id;

UPDATE document_types
SET document_category_id = (
    SELECT id FROM document_categories ORDER BY id ASC LIMIT 1
)
WHERE document_category_id IS NULL;

ALTER TABLE document_types
MODIFY COLUMN document_category_id INT UNSIGNED NOT NULL;

ALTER TABLE document_types
ADD CONSTRAINT fk_document_types_category FOREIGN KEY (document_category_id) REFERENCES document_categories(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE document_types
DROP INDEX uniq_document_types_name,
ADD UNIQUE KEY uniq_document_types_category_name (document_category_id, name);

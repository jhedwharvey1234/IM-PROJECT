ALTER TABLE documents
ADD COLUMN IF NOT EXISTS document_category_id INT UNSIGNED NULL AFTER subject,
ADD COLUMN IF NOT EXISTS document_type_id INT UNSIGNED NULL AFTER document_category_id;

ALTER TABLE documents
ADD CONSTRAINT fk_documents_category FOREIGN KEY (document_category_id) REFERENCES document_categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT fk_documents_type FOREIGN KEY (document_type_id) REFERENCES document_types(id) ON DELETE SET NULL ON UPDATE CASCADE;
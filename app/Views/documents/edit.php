<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; overflow-y: auto; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .section-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #0d6efd; }
        .section-card h5 { color: #0d6efd; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; font-size: 16px; }
        .section-card h5 i { margin-right: 10px; font-size: 18px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit Document']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('documents') ?>" title="Go to Documents">Document Management</a>
            <span class="separator">›</span>
            <span class="current">Edit: <?= esc($document['title']) ?></span>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <h5 class="mb-3"><i class="bi bi-exclamation-circle"></i> Validation Errors</h5>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('documents/update/' . $document['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="section-card">
                <h5><i class="bi bi-file-earmark-text"></i> Document Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $document['title']) ?>" maxlength="200" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject', $document['subject']) ?>" maxlength="200">
                    </div>
                    <div class="col-md-6">
                        <label for="document_category_id" class="form-label">Document Category</label>
                        <select class="form-select" id="document_category_id" name="document_category_id">
                            <option value="">Select Category</option>
                            <?php foreach (($documentCategories ?? []) as $category): ?>
                                <?php $selectedCategoryId = old('document_category_id', $document['document_category_id'] ?? ''); ?>
                                <option value="<?= (int) $category['id'] ?>" <?= (string) $selectedCategoryId === (string) $category['id'] ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="document_type_id" class="form-label">Document Type</label>
                        <select class="form-select" id="document_type_id" name="document_type_id">
                            <option value="">Select Type</option>
                            <?php foreach (($documentTypes ?? []) as $type): ?>
                                <?php $selectedTypeId = old('document_type_id', $document['document_type_id'] ?? ''); ?>
                                <option
                                    value="<?= (int) $type['id'] ?>"
                                    data-category-id="<?= (int) ($type['document_category_id'] ?? 0) ?>"
                                    <?= (string) $selectedTypeId === (string) $type['id'] ? 'selected' : '' ?>
                                >
                                    <?= esc($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="6" placeholder="Enter document description..."><?= old('description', $document['description']) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label for="detailsEditor" class="form-label">Details</label>
                        <textarea class="form-control" id="detailsEditor" name="details" rows="8"><?= esc((string) old('details', $document['details'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update Document
                </button>
                <a href="<?= site_url('documents') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.8.0/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        (function () {
            const categorySelect = document.getElementById('document_category_id');
            const typeSelect = document.getElementById('document_type_id');

            const filterTypesByCategory = () => {
                if (!categorySelect || !typeSelect) {
                    return;
                }

                const selectedCategoryId = categorySelect.value;
                const currentType = typeSelect.value;
                let hasCurrentType = false;

                Array.from(typeSelect.options).forEach((option, index) => {
                    if (index === 0) {
                        option.hidden = false;
                        return;
                    }

                    const optionCategoryId = option.dataset.categoryId || '';
                    const isVisible = selectedCategoryId !== '' && optionCategoryId === selectedCategoryId;

                    option.hidden = !isVisible;

                    if (isVisible && option.value === currentType) {
                        hasCurrentType = true;
                    }
                });

                if (selectedCategoryId === '') {
                    typeSelect.value = '';
                    typeSelect.disabled = true;
                } else {
                    typeSelect.disabled = false;
                    if (!hasCurrentType) {
                        typeSelect.value = '';
                    }
                }
            };

            if (categorySelect && typeSelect) {
                categorySelect.addEventListener('change', filterTypesByCategory);
                filterTypesByCategory();
            }

            tinymce.init({
                selector: '#detailsEditor',
                height: 420,
                menubar: 'file edit view insert format tools table help',
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount help',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table media | removeformat | code fullscreen preview',
                toolbar_mode: 'wrap',
                branding: false,
                promotion: false,
                automatic_uploads: true,
                image_title: true,
                file_picker_types: 'image',
                images_upload_handler: (blobInfo) => {
                    return new Promise((resolve) => {
                        resolve('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
                    });
                },
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
            });
        })();
    </script>
</body>
</html>

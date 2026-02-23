<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit DCF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .part-card { border: 1px solid #ced4da; border-radius: 8px; padding: 15px; margin-bottom: 14px; background: #f8f9fa; }
        .question-card { border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; margin-bottom: 12px; background: #fff; }
        .option-row { display: flex; gap: 8px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit DCF']) ?>

    <div class="main-content">
        <h1>Edit DCF</h1>
        <a href="<?= site_url('dcf') ?>" class="btn btn-secondary mb-3">Back to DCF</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('dcf/update/' . $dcf['id']) ?>" method="post" class="row g-3" id="dcfForm">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title', esc($dcf['title'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="due_date" class="form-label">Due Date *</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?= old('due_date', esc($dcf['due_date'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="department_id" class="form-label">Department *</label>
                <select class="form-select" id="department_id" name="department_id" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <?php $selectedDepartment = old('department_id', (int) ($dcf['department_id'] ?? 0)); ?>
                        <option value="<?= $department['id'] ?>" <?= (int) $selectedDepartment === (int) $department['id'] ? 'selected' : '' ?>>
                            <?= esc($department['department_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', esc($dcf['description'] ?? '')) ?></textarea>
            </div>

            <div class="col-12">
                <h5 class="mb-2">DCF Parts</h5>
                <div id="partsContainer"></div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addPartBtn"><i class="bi bi-plus-circle"></i> Add Part</button>
                <small class="text-muted d-block mt-2">Edit part title/description and add or remove questions. Rate Me type uses 1-10.</small>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update DCF</button>
                <a href="<?= site_url('dcf') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ANSWER_TYPES_WITH_OPTIONS = ['multiple_choice', 'checkbox', 'dropdown'];
        const partsContainer = document.getElementById('partsContainer');
        const addPartBtn = document.getElementById('addPartBtn');

        let partIndex = 0;
        const questionIndexByPart = {};

        const oldParts = <?= json_encode(old('parts') ?: null, JSON_UNESCAPED_UNICODE) ?>;
        const existingParts = <?= json_encode(array_map(static function ($part) {
            return [
                'title' => $part['title'] ?? '',
                'description' => $part['description'] ?? '',
                'questions' => array_map(static function ($q) {
                    return [
                        'question_text' => $q['question_text'] ?? '',
                        'is_required' => !empty($q['is_required']) ? 1 : 0,
                        'answer_type' => $q['answer_type'] ?? 'short_answer',
                        'rate_min' => $q['rate_min'] ?? 1,
                        'rate_max' => $q['rate_max'] ?? 10,
                        'options' => array_map(static function ($opt) {
                            return $opt['option_text'] ?? '';
                        }, $q['options'] ?? []),
                    ];
                }, $part['questions'] ?? []),
            ];
        }, $parts ?? []), JSON_UNESCAPED_UNICODE) ?>;

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function createOptionRow(partIdx, qIdx, value = '') {
            const row = document.createElement('div');
            row.className = 'option-row';
            row.innerHTML = `
                <input type="text" class="form-control form-control-sm" name="parts[${partIdx}][questions][${qIdx}][options][]" placeholder="Option value" value="${escapeHtml(value)}">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.option-row').remove()"><i class="bi bi-x-lg"></i></button>
            `;
            return row;
        }

        function toggleOptionsArea(questionBlock, answerType) {
            const optionsArea = questionBlock.querySelector('.options-area');
            const rateRangeArea = questionBlock.querySelector('.rate-range-area');
            if (ANSWER_TYPES_WITH_OPTIONS.includes(answerType)) {
                optionsArea.style.display = '';
                const list = optionsArea.querySelector('.options-list');
                if (!list.querySelector('.option-row')) {
                    const partIdx = questionBlock.dataset.partIndex;
                    const qIdx = questionBlock.dataset.questionIndex;
                    list.appendChild(createOptionRow(partIdx, qIdx));
                }
            } else {
                optionsArea.style.display = 'none';
            }

            if (answerType === 'rate_me') {
                rateRangeArea.style.display = '';
            } else {
                rateRangeArea.style.display = 'none';
            }
        }

        function addQuestionBlock(partBlock, question = null) {
            const partIdx = Number(partBlock.dataset.partIndex);
            const qIdx = questionIndexByPart[partIdx] || 0;
            questionIndexByPart[partIdx] = qIdx + 1;

            const questionText = question?.question_text || '';
            const isRequired = question?.is_required ? 'checked' : '';
            const answerType = question?.answer_type || 'short_answer';
            const options = Array.isArray(question?.options) ? question.options : [];
            const rateMin = Number.isFinite(Number(question?.rate_min)) ? Number(question.rate_min) : 1;
            const rateMax = Number.isFinite(Number(question?.rate_max)) ? Number(question.rate_max) : 10;

            const block = document.createElement('div');
            block.className = 'question-card';
            block.dataset.partIndex = String(partIdx);
            block.dataset.questionIndex = String(qIdx);
            block.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Question</strong>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-question-btn"><i class="bi bi-trash"></i></button>
                </div>
                <div class="mb-2">
                    <label class="form-label">Question Text</label>
                    <input type="text" class="form-control" name="parts[${partIdx}][questions][${qIdx}][question_text]" value="${escapeHtml(questionText)}" placeholder="Enter question" />
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="parts[${partIdx}][questions][${qIdx}][is_required]" value="1" ${isRequired}>
                            <label class="form-check-label">Required</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Answer Type</label>
                        <select class="form-select answer-type-select" name="parts[${partIdx}][questions][${qIdx}][answer_type]">
                            <option value="multiple_choice" ${answerType === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                            <option value="checkbox" ${answerType === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                            <option value="dropdown" ${answerType === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                            <option value="short_answer" ${answerType === 'short_answer' ? 'selected' : ''}>Short Answer</option>
                            <option value="paragraph" ${answerType === 'paragraph' ? 'selected' : ''}>Paragraph</option>
                            <option value="rate_me" ${answerType === 'rate_me' ? 'selected' : ''}>Rate Me</option>
                        </select>
                    </div>
                </div>
                <div class="rate-range-area mt-3" style="display:none;">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Rate Min</label>
                            <input type="number" class="form-control" min="1" name="parts[${partIdx}][questions][${qIdx}][rate_min]" value="${rateMin}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rate Max</label>
                            <input type="number" class="form-control" min="1" name="parts[${partIdx}][questions][${qIdx}][rate_max]" value="${rateMax}">
                        </div>
                    </div>
                    <small class="text-muted">Set the allowed rating range for this question.</small>
                </div>
                <div class="options-area mt-3" style="display:none;">
                    <label class="form-label">Answer Options</label>
                    <div class="options-list"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm add-option-btn mt-1"><i class="bi bi-plus"></i> Add Option</button>
                </div>
            `;

            block.querySelector('.remove-question-btn').addEventListener('click', () => block.remove());
            const select = block.querySelector('.answer-type-select');
            select.addEventListener('change', () => toggleOptionsArea(block, select.value));
            block.querySelector('.add-option-btn').addEventListener('click', () => {
                block.querySelector('.options-list').appendChild(createOptionRow(partIdx, qIdx));
            });

            const list = block.querySelector('.options-list');
            if (options.length) {
                options.forEach(opt => list.appendChild(createOptionRow(partIdx, qIdx, String(opt))));
            }

            partBlock.querySelector('.questions-container').appendChild(block);
            toggleOptionsArea(block, answerType);
        }

        function addPartBlock(part = null) {
            const pIdx = partIndex++;
            questionIndexByPart[pIdx] = 0;

            const partTitle = part?.title || '';
            const partDescription = part?.description || '';
            const questions = Array.isArray(part?.questions) ? part.questions : [];

            const block = document.createElement('div');
            block.className = 'part-card';
            block.dataset.partIndex = String(pIdx);
            block.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Part</h6>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-part-btn"><i class="bi bi-trash"></i></button>
                </div>
                <div class="mb-2">
                    <label class="form-label">Part Title *</label>
                    <input type="text" class="form-control" name="parts[${pIdx}][title]" value="${escapeHtml(partTitle)}" placeholder="Enter part title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Part Description</label>
                    <textarea class="form-control" rows="2" name="parts[${pIdx}][description]" placeholder="Enter part description">${escapeHtml(partDescription)}</textarea>
                </div>
                <div class="questions-container"></div>
                <button type="button" class="btn btn-outline-primary btn-sm add-question-btn"><i class="bi bi-plus-circle"></i> Add Question</button>
            `;

            block.querySelector('.remove-part-btn').addEventListener('click', () => block.remove());
            block.querySelector('.add-question-btn').addEventListener('click', () => addQuestionBlock(block));

            partsContainer.appendChild(block);
            questions.forEach(q => addQuestionBlock(block, q));
        }

        addPartBtn.addEventListener('click', () => addPartBlock());

        const initialParts = Array.isArray(oldParts) ? oldParts : existingParts;
        if (Array.isArray(initialParts) && initialParts.length > 0) {
            initialParts.forEach(p => addPartBlock(p));
        }

        if (!partsContainer.querySelector('.part-card')) {
            addPartBlock({ title: '', description: '', questions: [] });
        }
    </script>
</body>
</html>

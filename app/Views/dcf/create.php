<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create DCF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .part-card { border: 1px solid #dee2e6; border-radius: 10px; padding: 20px; margin-bottom: 18px; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .question-card { border-left: 4px solid #667eea; border-radius: 10px; padding: 16px; margin-bottom: 14px; background: #f8f9fa; }
        
        /* Option rendering - mirrors public form styling */
        .option-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 8px; background: white; border: 1px solid #f0f0f0; border-radius: 4px; }
        .option-row .form-check-input { margin-top: 0; flex-shrink: 0; }
        .option-input { flex: 1; }
        .option-row .btn { margin-left: auto; flex-shrink: 0; }
        
        /* Radio/Checkbox group styling */
        .radio-group, .checkbox-grid { display: flex; flex-direction: column; gap: 0.75rem; }
        .radio-group .form-check, .checkbox-grid .form-check { margin-bottom: 0; display: flex; align-items: center; }
        .radio-group .form-check-input, .checkbox-grid .form-check-input { margin-right: 0.5rem; margin-top: 0; }
        
        /* Checkbox grid - 2 column layout */
        .checkbox-grid { flex-direction: row; flex-wrap: wrap; }
        .checkbox-grid .form-check { flex: 0 0 calc(50% - 0.375rem); }
        
        /* Rate scale styling */
        .rate-grid { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
        .rate-grid .rate-option-btn { cursor: pointer; padding: 0.5rem 0.75rem; border: 2px solid #dee2e6; border-radius: 6px; text-align: center; transition: all 0.2s; font-weight: 500; background: white; min-width: 45px; }
        .rate-grid .rate-option-btn:hover { border-color: #667eea; background: #f0f3ff; }
        .rate-grid .rate-option-btn.active { background: #667eea; color: white; border-color: #667eea; }
        .rate-grid .rate-option-btn input { display: none; }
        
        /* Dropdown preview */
        .dropdown-preview { display: none; }
        .dropdown-preview.active { display: block; }
        .dropdown-preview select { max-width: 100%; }
        
        /* Grid builder - matches advance checkbox layout */
        .grid-builder { border: 1px solid #dee2e6; border-radius: 8px; overflow-x: auto; }
        .grid-builder table { min-width: 640px; margin-bottom: 0; }
        .grid-builder th, .grid-builder td { text-align: center; padding: 10px; white-space: nowrap; border: 1px solid #dee2e6; }
        .grid-builder .grid-row-label { text-align: left; background: #f8f9fa; font-weight: 600; }
        .grid-row-input, .grid-col-input { min-width: 140px; }
        
        .part-preview { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .part-preview-title { font-weight: 600; color: #2f3550; }
        .question-preview { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; font-weight: 600; }
        .question-preview-text { color: #2f3550; }
        .preview-type { font-weight: 500; }
        .required-mark { color: #dc3545; font-weight: 700; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Create DCF']) ?>

    <div class="main-content">
        <h1>Create DCF</h1>
        <a href="<?= site_url('dcf') ?>" class="btn btn-secondary mb-3">Back to DCF</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <strong>Errors:</strong>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= site_url('dcf/store') ?>" method="post" class="row g-3" id="dcfForm">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
            </div>
            <div class="col-md-6">
                <label for="due_date" class="form-label">Due Date *</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?= old('due_date') ?>" required>
            </div>
            <div class="col-md-6">
                <label for="department_id" class="form-label">Department *</label>
                <select class="form-select" id="department_id" name="department_id" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['id'] ?>" <?= old('department_id') == $department['id'] ? 'selected' : '' ?>>
                            <?= esc($department['department_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="respondents_needed" class="form-label">Number of Respondents Needed</label>
                <input type="number" class="form-control" id="respondents_needed" name="respondents_needed" value="<?= old('respondents_needed') ?>" min="1" step="1" placeholder="Optional target">
                <small class="text-muted">When this target is reached, a notification will be created.</small>
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">DCF Parts</h5>
                    <div class="gap-2" style="display: flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="pastPartsBtn" disabled>
                            <i class="bi bi-folder-symlink"></i> Past Parts
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="pastQuestionsBtn" disabled>
                            <i class="bi bi-clock-history"></i> Past Questions
                        </button>
                    </div>
                </div>
                <div id="partsContainer"></div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addPartBtn">
                    <i class="bi bi-plus-circle"></i> Add Part
                </button>
                <small class="text-muted d-block mt-2">Each part has title and description. Inside each part, add questions. Answer type supports Rate Me (1-10).</small>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create DCF</button>
                <a href="<?= site_url('dcf') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <div class="modal fade" id="pastQuestionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Past Questions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pastQuestionsLoading" class="text-muted d-none">Loading questions...</div>
                    <div id="pastQuestionsEmpty" class="text-muted d-none">No past questions found for selected department.</div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle" id="pastQuestionsTable">
                            <thead>
                                <tr>
                                    <th>Department ID</th>
                                    <th>DCF ID</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="pastQuestionsTbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pastPartsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Past Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pastPartsLoading" class="text-muted d-none">Loading parts...</div>
                    <div id="pastPartsEmpty" class="text-muted d-none">No past parts found for selected department.</div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle" id="pastPartsTable">
                            <thead>
                                <tr>
                                    <th>Part Title</th>
                                    <th>Description</th>
                                    <th>Questions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="pastPartsTbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ANSWER_TYPES_WITH_OPTIONS = ['multiple_choice', 'checkbox', 'dropdown'];
        const partsContainer = document.getElementById('partsContainer');
        const addPartBtn = document.getElementById('addPartBtn');
        const departmentSelect = document.getElementById('department_id');
        const pastQuestionsBtn = document.getElementById('pastQuestionsBtn');
        const pastPartsBtn = document.getElementById('pastPartsBtn');
        const pastQuestionsModalEl = document.getElementById('pastQuestionsModal');
        const pastPartsModalEl = document.getElementById('pastPartsModal');
        const pastQuestionsModal = new bootstrap.Modal(pastQuestionsModalEl);
        const pastPartsModal = new bootstrap.Modal(pastPartsModalEl);
        const pastQuestionsLoading = document.getElementById('pastQuestionsLoading');
        const pastQuestionsEmpty = document.getElementById('pastQuestionsEmpty');
        const pastQuestionsTbody = document.getElementById('pastQuestionsTbody');
        const pastPartsLoading = document.getElementById('pastPartsLoading');
        const pastPartsEmpty = document.getElementById('pastPartsEmpty');
        const pastPartsTbody = document.getElementById('pastPartsTbody');

        const oldParts = <?= json_encode(old('parts') ?: [], JSON_UNESCAPED_UNICODE) ?>;
        const userRoles = <?= json_encode($userRoles ?? [], JSON_UNESCAPED_UNICODE) ?>;

        let partIndex = 0;
        const questionIndexByPart = {};

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildRoleOptions(selectedRole = 'all', includeInherit = false) {
            const options = [];

            if (includeInherit) {
                const inheritSelected = selectedRole === '' ? 'selected' : '';
                options.push(`<option value="" ${inheritSelected}>Inherit from Part Role</option>`);
            }

            const allSelected = selectedRole === 'all' ? 'selected' : '';
            options.push(`<option value="all" ${allSelected}>All Roles</option>`);

            if (Array.isArray(userRoles)) {
                userRoles.forEach((role) => {
                    const roleKey = String(role.role_key || '').trim();
                    if (!roleKey) {
                        return;
                    }

                    const roleName = String(role.role_name || roleKey);
                    const selected = selectedRole === roleKey ? 'selected' : '';
                    options.push(`<option value="${escapeHtml(roleKey)}" ${selected}>${escapeHtml(roleName)}</option>`);
                });
            }

            return options.join('');
        }

        function createOptionRow(partIdx, qIdx, value = '', answerType = 'multiple_choice') {
            const row = document.createElement('div');
            row.className = 'option-row';

            // Create a preview area showing how the option will look in the public form
            let checkboxHtml = '';
            if (answerType === 'multiple_choice') {
                checkboxHtml = '<input class="form-check-input" type="radio" disabled>';
            } else if (answerType === 'checkbox') {
                checkboxHtml = '<input class="form-check-input" type="checkbox" disabled>';
            } else if (answerType === 'dropdown') {
                checkboxHtml = '<i class="bi bi-caret-down-fill text-muted" style="font-size: 0.8rem;"></i>';
            }

            row.innerHTML = `
                ${checkboxHtml}
                <input type="text" class="form-control form-control-sm option-input" name="parts[${partIdx}][questions][${qIdx}][options][]" placeholder="Option value" value="${escapeHtml(value)}">
                <button type="button" class="btn btn-outline-danger btn-sm remove-option-btn"><i class="bi bi-x-lg"></i></button>
            `;
            return row;
        }

        function toggleOptionsArea(questionBlock, answerType) {
            const optionsArea = questionBlock.querySelector('.options-area');
            const rateRangeArea = questionBlock.querySelector('.rate-range-area');
            const gridArea = questionBlock.querySelector('.grid-options-area');
            const allowMultipleArea = questionBlock.querySelector('.allow-multiple-area');

            // Show options area for question types with options
            if (ANSWER_TYPES_WITH_OPTIONS.includes(answerType)) {
                optionsArea.style.display = '';
                renderOptionRows(questionBlock, answerType, getOptionValues(questionBlock));
            } else {
                optionsArea.style.display = 'none';
            }

            // Show rate range for rate_me
            if (answerType === 'rate_me') {
                rateRangeArea.style.display = '';
            } else {
                rateRangeArea.style.display = 'none';
            }

            // Show grid area for advance_checkbox
            if (answerType === 'advance_checkbox') {
                gridArea.style.display = '';
                const gridValues = getGridValues(questionBlock);
                renderGridBuilder(questionBlock, gridValues.rows, gridValues.columns);
            } else {
                gridArea.style.display = 'none';
            }

            // Show allow_multiple toggle for types that support it
            const typesWithMultipleSupport = ['multiple_choice', 'checkbox', 'dropdown', 'advance_checkbox'];
            if (typesWithMultipleSupport.includes(answerType)) {
                allowMultipleArea.style.display = '';
            } else {
                allowMultipleArea.style.display = 'none';
            }

            syncDropdownPreview(questionBlock);
        }

        function normalizeGridValue(value) {
            if (Array.isArray(value)) {
                return value.join('\n');
            }
            if (typeof value === 'string') {
                return value;
            }
            return '';
        }

        function parseLines(value) {
            return String(value || '')
                .split(/\r\n|\r|\n/)
                .map(line => line.trim())
                .filter(Boolean);
        }

        function getOptionValues(questionBlock) {
            return Array.from(questionBlock.querySelectorAll('.option-input'))
                .map(input => input.value.trim())
                .filter(Boolean);
        }

        function renderOptionRows(questionBlock, answerType, values = []) {
            const list = questionBlock.querySelector('.options-list');
            if (!list) {
                return;
            }

            const partIdx = questionBlock.dataset.partIndex;
            const qIdx = questionBlock.dataset.questionIndex;
            const rows = values.length > 0 ? values : [''];

            // Clear and render with appropriate container class
            list.className = 'options-list';
            if (answerType === 'checkbox') {
                list.classList.add('checkbox-grid');
            } else if (answerType === 'multiple_choice') {
                list.classList.add('radio-group');
            }

            list.innerHTML = '';
            rows.forEach(value => {
                list.appendChild(createOptionRow(partIdx, qIdx, value, answerType));
            });

            syncDropdownPreview(questionBlock);
        }

        function syncDropdownPreview(questionBlock) {
            const answerType = questionBlock.querySelector('.answer-type-select')?.value || '';
            const preview = questionBlock.querySelector('.dropdown-preview');
            if (!preview) {
                return;
            }

            if (answerType !== 'dropdown') {
                preview.classList.remove('active');
                return;
            }

            const select = preview.querySelector('select');
            const values = getOptionValues(questionBlock);
            const options = values.map(value => `<option>${escapeHtml(value)}</option>`).join('');
            select.innerHTML = `<option>-- Select --</option>${options}`;
            preview.classList.add('active');
        }

        function getGridValues(questionBlock) {
            const rows = Array.from(questionBlock.querySelectorAll('.grid-row-input'))
                .map(input => input.value.trim())
                .filter(Boolean);
            const columns = Array.from(questionBlock.querySelectorAll('.grid-col-input'))
                .map(input => input.value.trim())
                .filter(Boolean);

            return { rows, columns };
        }

        function renderRatePreview(questionBlock) {
            const rateMin = parseInt(questionBlock.querySelector('.rate-min-input')?.value || '1', 10);
            const rateMax = parseInt(questionBlock.querySelector('.rate-max-input')?.value || '10', 10);
            const preview = questionBlock.querySelector('.rate-preview');
            if (!preview) {
                return;
            }

            preview.innerHTML = '';
            for (let i = rateMin; i <= rateMax; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'rate-option-btn';
                btn.disabled = true;
                btn.innerHTML = `<span>${i}</span>`;
                preview.appendChild(btn);
            }
        }

        function renderGridBuilder(questionBlock, rows = [], columns = []) {
            const builder = questionBlock.querySelector('.grid-builder');
            if (!builder) {
                return;
            }

            const partIdx = questionBlock.dataset.partIndex;
            const qIdx = questionBlock.dataset.questionIndex;
            const rowValues = rows.length > 0 ? rows : ['Row 1'];
            const colValues = columns.length > 0 ? columns : ['Column 1'];

            const headerCells = colValues.map(value => `
                <th>
                    <input type="text" class="form-control form-control-sm grid-col-input" name="parts[${partIdx}][questions][${qIdx}][grid_columns][]" value="${escapeHtml(value)}">
                </th>
            `).join('');

            const bodyRows = rowValues.map(value => {
                const cells = colValues.map(() => '<td><input type="checkbox" disabled></td>').join('');
                return `
                    <tr>
                        <td>
                            <input type="text" class="form-control form-control-sm grid-row-input" name="parts[${partIdx}][questions][${qIdx}][grid_rows][]" value="${escapeHtml(value)}">
                        </td>
                        ${cells}
                    </tr>
                `;
            }).join('');

            builder.innerHTML = `
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th></th>
                            ${headerCells}
                        </tr>
                    </thead>
                    <tbody>
                        ${bodyRows}
                    </tbody>
                </table>
            `;
        }

        function addQuestionBlock(partBlock, question = null) {
            const partIdx = Number(partBlock.dataset.partIndex);
            const qIdx = questionIndexByPart[partIdx] || 0;
            questionIndexByPart[partIdx] = qIdx + 1;

            const questionText = question?.question_text || '';
            const isRequired = question?.is_required ? 'checked' : '';
            const answerType = question?.answer_type || 'short_answer';
            const questionRoleKey = question?.role_key || '';
            const allowMultiple = question?.allow_multiple ? 'checked' : '';
            const options = Array.isArray(question?.options) ? question.options : [];
            const rateMin = Number.isFinite(Number(question?.rate_min)) ? Number(question.rate_min) : 1;
            const rateMax = Number.isFinite(Number(question?.rate_max)) ? Number(question.rate_max) : 10;
            const gridRowsValue = normalizeGridValue(question?.grid_rows);
            const gridColumnsValue = normalizeGridValue(question?.grid_columns);

            const block = document.createElement('div');
            block.className = 'question-card';
            block.dataset.partIndex = String(partIdx);
            block.dataset.questionIndex = String(qIdx);
            block.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="question-preview">
                        <span class="question-preview-text">${escapeHtml(questionText || 'Question')}</span>
                        <span class="required-mark preview-required" style="${isRequired ? '' : 'display:none;'}">*</span>
                        <span class="badge bg-light text-dark preview-type">${escapeHtml(answerType.replace('_', ' '))}</span>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-question-btn"><i class="bi bi-trash"></i></button>
                </div>
                <div class="mb-2">
                    <label class="form-label">Question Text</label>
                    <input type="text" class="form-control question-text-input" name="parts[${partIdx}][questions][${qIdx}][question_text]" value="${escapeHtml(questionText)}" placeholder="Enter question" />
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input required-toggle" type="checkbox" name="parts[${partIdx}][questions][${qIdx}][is_required]" value="1" ${isRequired}>
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
                            <option value="wysiwyg" ${answerType === 'wysiwyg' ? 'selected' : ''}>WYSIWYG</option>
                            <option value="advance_checkbox" ${answerType === 'advance_checkbox' ? 'selected' : ''}>Advance Checkbox</option>
                            <option value="rate_me" ${answerType === 'rate_me' ? 'selected' : ''}>Rate Me</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <label class="form-label">Question Role</label>
                    <select class="form-select" name="parts[${partIdx}][questions][${qIdx}][role_key]">
                        ${buildRoleOptions(questionRoleKey, true)}
                    </select>
                    <small class="text-muted">Leave as inherit to use this part role.</small>
                </div>
                <div class="allow-multiple-area mt-2" style="display:none;">
                    <div class="form-check form-switch">
                        <input class="form-check-input allow-multiple-toggle" type="checkbox" name="parts[${partIdx}][questions][${qIdx}][allow_multiple]" value="1" ${allowMultiple}>
                        <label class="form-check-label"><i class="bi bi-check2-square"></i> Allow Multiple Answers</label>
                    </div>
                    <small class="text-muted">Enable to allow respondents to select multiple options.</small>
                </div>
                <div class="rate-range-area mt-3" style="display:none;">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Rate Min</label>
                            <input type="number" class="form-control rate-min-input" min="1" name="parts[${partIdx}][questions][${qIdx}][rate_min]" value="${rateMin}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rate Max</label>
                            <input type="number" class="form-control rate-max-input" min="1" name="parts[${partIdx}][questions][${qIdx}][rate_max]" value="${rateMax}">
                        </div>
                    </div>
                    <small class="text-muted">Set the allowed rating range for this question.</small>
                    <label class="form-label mt-3">Preview:</label>
                    <div class="rate-grid rate-preview" style="gap: 0.5rem;"></div>
                </div>
                <div class="grid-options-area mt-3" style="display:none;">
                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm add-grid-row-btn"><i class="bi bi-plus"></i> Add Row</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm add-grid-col-btn"><i class="bi bi-plus"></i> Add Column</button>
                    </div>
                    <div class="grid-builder"></div>
                    <small class="text-muted">Used for Advance Checkbox grid.</small>
                </div>
                <div class="options-area mt-3" style="display:none;">
                    <label class="form-label">Answer Options</label>
                    <div class="dropdown-preview mb-2"><select class="form-select" disabled></select></div>
                    <div class="options-list"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm add-option-btn mt-1"><i class="bi bi-plus"></i> Add Option</button>
                </div>
            `;

            block.querySelector('.remove-question-btn').addEventListener('click', () => block.remove());

            const select = block.querySelector('.answer-type-select');
            select.addEventListener('change', () => {
                toggleOptionsArea(block, select.value);
                const typeBadge = block.querySelector('.preview-type');
                typeBadge.textContent = select.value.replace('_', ' ');
            });

            const questionInput = block.querySelector('.question-text-input');
            const previewText = block.querySelector('.question-preview-text');
            questionInput.addEventListener('input', () => {
                const value = questionInput.value.trim();
                previewText.textContent = value !== '' ? value : 'Question';
            });

            const requiredToggle = block.querySelector('.required-toggle');
            const previewRequired = block.querySelector('.preview-required');
            requiredToggle.addEventListener('change', () => {
                previewRequired.style.display = requiredToggle.checked ? '' : 'none';
            });

            const addOptionBtn = block.querySelector('.add-option-btn');
            addOptionBtn.addEventListener('click', () => {
                const answerTypeValue = select.value || 'multiple_choice';
                block.querySelector('.options-list').appendChild(createOptionRow(partIdx, qIdx, '', answerTypeValue));
                syncDropdownPreview(block);
            });

            const optionsArea = block.querySelector('.options-area');
            optionsArea.addEventListener('input', (event) => {
                if (event.target.classList.contains('option-input')) {
                    syncDropdownPreview(block);
                }
            });
            optionsArea.addEventListener('click', (event) => {
                const removeBtn = event.target.closest('.remove-option-btn');
                if (removeBtn) {
                    removeBtn.closest('.option-row')?.remove();
                    syncDropdownPreview(block);
                }
            });

            const addGridRowBtn = block.querySelector('.add-grid-row-btn');
            const addGridColBtn = block.querySelector('.add-grid-col-btn');
            addGridRowBtn.addEventListener('click', () => {
                const gridValues = getGridValues(block);
                gridValues.rows.push('');
                renderGridBuilder(block, gridValues.rows, gridValues.columns);
            });
            addGridColBtn.addEventListener('click', () => {
                const gridValues = getGridValues(block);
                gridValues.columns.push('');
                renderGridBuilder(block, gridValues.rows, gridValues.columns);
            });

            // Rate preview update
            const rateMinInput = block.querySelector('.rate-min-input');
            const rateMaxInput = block.querySelector('.rate-max-input');
            if (rateMinInput && rateMaxInput) {
                rateMinInput.addEventListener('input', () => renderRatePreview(block));
                rateMaxInput.addEventListener('input', () => renderRatePreview(block));
            }

            renderOptionRows(block, answerType, options);
            renderGridBuilder(block, parseLines(gridRowsValue), parseLines(gridColumnsValue));
            renderRatePreview(block);

            partBlock.querySelector('.questions-container').appendChild(block);
            toggleOptionsArea(block, answerType);
        }

        function addPartBlock(part = null) {
            const pIdx = partIndex++;
            questionIndexByPart[pIdx] = 0;

            const partTitle = part?.title || '';
            const partDescription = part?.description || '';
            const partRoleKey = part?.role_key || 'all';
            const questions = Array.isArray(part?.questions) ? part.questions : [];

            const block = document.createElement('div');
            block.className = 'part-card';
            block.dataset.partIndex = String(pIdx);
            block.innerHTML = `
                <div class="part-preview">
                    <div class="part-preview-title">Part: <span class="part-title-preview">${escapeHtml(partTitle || 'Untitled Part')}</span></div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-part-btn"><i class="bi bi-trash"></i></button>
                </div>
                <div class="mb-2">
                    <label class="form-label">Part Title *</label>
                    <input type="text" class="form-control part-title-input" name="parts[${pIdx}][title]" value="${escapeHtml(partTitle)}" placeholder="Enter part title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Part Description</label>
                    <textarea class="form-control" rows="2" name="parts[${pIdx}][description]" placeholder="Enter part description">${escapeHtml(partDescription)}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Part Role</label>
                    <select class="form-select" name="parts[${pIdx}][role_key]">
                        ${buildRoleOptions(partRoleKey, false)}
                    </select>
                    <small class="text-muted">Default is All Roles. Questions can inherit this role.</small>
                </div>
                <div class="questions-container"></div>
                <button type="button" class="btn btn-outline-primary btn-sm add-question-btn"><i class="bi bi-plus-circle"></i> Add Question</button>
            `;

            block.querySelector('.remove-part-btn').addEventListener('click', () => {
                block.remove();
            });
            block.querySelector('.add-question-btn').addEventListener('click', () => addQuestionBlock(block));

            const partTitleInput = block.querySelector('.part-title-input');
            const partTitlePreview = block.querySelector('.part-title-preview');
            partTitleInput.addEventListener('input', () => {
                const value = partTitleInput.value.trim();
                partTitlePreview.textContent = value !== '' ? value : 'Untitled Part';
            });

            partsContainer.appendChild(block);

            questions.forEach(q => addQuestionBlock(block, q));
        }

        function setPastQuestionsButtonState() {
            const departmentId = parseInt(departmentSelect.value || '0', 10);
            pastQuestionsBtn.disabled = !(departmentId > 0);
            pastPartsBtn.disabled = !(departmentId > 0);
        }

        function insertPastPartIntoParts(part) {
            addPartBlock({
                title: part.title,
                description: part.description,
                role_key: part.role_key || 'all',
                questions: Array.isArray(part.questions) ? part.questions : []
            });
        }

        function renderPastPartsRows(rows) {
            pastPartsTbody.innerHTML = '';
            if (!Array.isArray(rows) || rows.length === 0) {
                pastPartsEmpty.classList.remove('d-none');
                return;
            }

            pastPartsEmpty.classList.add('d-none');
            rows.forEach((row) => {
                const questionCount = Array.isArray(row.questions) ? row.questions.length : 0;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${escapeHtml(row.title ?? '')}</td>
                    <td>${escapeHtml(row.description ?? '')}</td>
                    <td><span class="badge bg-info">${questionCount}</span></td>
                    <td>
                        <button class="btn btn-sm btn-success insert-past-part-btn" type="button">
                            <i class="bi bi-plus-lg"></i> Insert
                        </button>
                    </td>
                `;

                tr.querySelector('.insert-past-part-btn').addEventListener('click', () => {
                    insertPastPartIntoParts(row);
                    pastPartsModal.hide();
                });

                pastPartsTbody.appendChild(tr);
            });
        }

        async function loadPastParts() {
            const departmentId = parseInt(departmentSelect.value || '0', 10);
            if (!(departmentId > 0)) {
                return;
            }

            pastPartsLoading.classList.remove('d-none');
            pastPartsEmpty.classList.add('d-none');
            pastPartsTbody.innerHTML = '';

            try {
                const response = await fetch(`<?= site_url('dcf/past-parts') ?>/${departmentId}`);
                const data = await response.json();
                if (data && data.success) {
                    renderPastPartsRows(data.parts || []);
                } else {
                    renderPastPartsRows([]);
                }
            } catch (error) {
                renderPastPartsRows([]);
            } finally {
                pastPartsLoading.classList.add('d-none');
            }
        }

        function getFirstPartBlock() {
            let firstPart = partsContainer.querySelector('.part-card');
            if (!firstPart) {
                addPartBlock({ title: 'Part 1', description: '', questions: [] });
                firstPart = partsContainer.querySelector('.part-card');
            }
            return firstPart;
        }

        function getPartsForInsertMenu() {
            const partBlocks = Array.from(partsContainer.querySelectorAll('.part-card'));
            if (partBlocks.length === 0) {
                const firstPart = getFirstPartBlock();
                const partIdx = firstPart.dataset.partIndex;
                return [{ partIndex: String(partIdx), partTitle: 'Part 1' }];
            }

            return partBlocks.map((partBlock, index) => {
                const partIdx = partBlock.dataset.partIndex;
                const titleInput = partBlock.querySelector(`input[name="parts[${partIdx}][title]"]`);
                const partTitle = titleInput && titleInput.value.trim() !== '' ? titleInput.value.trim() : `Part ${index + 1}`;
                return { partIndex: String(partIdx), partTitle };
            });
        }

        function insertPastQuestionIntoPart(partIndex, row) {
            const targetPart = partsContainer.querySelector(`.part-card[data-part-index="${partIndex}"]`) || getFirstPartBlock();
            addQuestionBlock(targetPart, {
                question_text: row.question_text || '',
                is_required: row.is_required ? 1 : 0,
                answer_type: row.answer_type || 'short_answer',
                role_key: row.role_key || '',
                options: Array.isArray(row.options) ? row.options : [],
                rate_min: row.rate_min ?? 1,
                rate_max: row.rate_max ?? 10,
                grid_rows: row.grid_rows ?? [],
                grid_columns: row.grid_columns ?? [],
            });
        }

        function renderPastQuestionsRows(rows) {
            pastQuestionsTbody.innerHTML = '';
            if (!Array.isArray(rows) || rows.length === 0) {
                pastQuestionsEmpty.classList.remove('d-none');
                return;
            }

            pastQuestionsEmpty.classList.add('d-none');
            rows.forEach((row) => {
                const parts = getPartsForInsertMenu();
                const insertMenuItems = parts.map((part) =>
                    `<li><button type="button" class="dropdown-item insert-question-to-part-btn" data-part-index="${escapeHtml(part.partIndex)}">${escapeHtml(part.partTitle)}</button></li>`
                ).join('');

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${escapeHtml(row.department_id ?? '')}</td>
                    <td>${escapeHtml(row.dcf_id ?? '')}</td>
                    <td>${escapeHtml(row.question_text ?? '')}</td>
                    <td>${escapeHtml(row.answer_type ?? '')}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Insert
                            </button>
                            <ul class="dropdown-menu">
                                ${insertMenuItems}
                            </ul>
                        </div>
                    </td>
                `;

                tr.querySelectorAll('.insert-question-to-part-btn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const partIndex = btn.getAttribute('data-part-index') || '';
                        insertPastQuestionIntoPart(partIndex, row);
                    });
                });

                pastQuestionsTbody.appendChild(tr);
            });
        }

        async function loadPastQuestions() {
            const departmentId = parseInt(departmentSelect.value || '0', 10);
            if (!(departmentId > 0)) {
                return;
            }

            pastQuestionsLoading.classList.remove('d-none');
            pastQuestionsEmpty.classList.add('d-none');
            pastQuestionsTbody.innerHTML = '';

            try {
                const response = await fetch(`<?= site_url('dcf/past-questions') ?>/${departmentId}`);
                const data = await response.json();
                if (data && data.success) {
                    renderPastQuestionsRows(data.questions || []);
                } else {
                    renderPastQuestionsRows([]);
                }
            } catch (error) {
                renderPastQuestionsRows([]);
            } finally {
                pastQuestionsLoading.classList.add('d-none');
            }
        }

        addPartBtn.addEventListener('click', () => addPartBlock());
        departmentSelect.addEventListener('change', setPastQuestionsButtonState);
        pastQuestionsBtn.addEventListener('click', async () => {
            await loadPastQuestions();
            pastQuestionsModal.show();
        });
        pastPartsBtn.addEventListener('click', async () => {
            await loadPastParts();
            pastPartsModal.show();
        });

        if (Array.isArray(oldParts) && oldParts.length > 0) {
            oldParts.forEach(p => addPartBlock(p));
        }

        if (!partsContainer.querySelector('.part-card')) {
            addPartBlock({ title: '', description: '', questions: [] });
        }

        setPastQuestionsButtonState();
    </script>
</body>
</html>

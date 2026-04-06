<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($dcf['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/IM/public/css/responsive-global.css">
    <style>
        body { background: linear-gradient(135deg, #f1f1f1 0%, #f1f1f1 100%); min-height: 100vh; padding: 2rem 0; }
        .form-container { background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .form-header { background: linear-gradient(135deg, #3b3b3b 0%, #3b3b3b 100%); color: white; padding: 2rem; border-radius: 12px 12px 0 0; }
        .progress-indicator { background: #f8f9fa; padding: 1.5rem; border-bottom: 1px solid #dee2e6; }
        .page-content { padding: 2rem; min-height: 400px; }
        .part-block { padding: 1.5rem; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 1.5rem; background: #ffffff; }
        .question-block { padding: 1.5rem; border-left: 4px solid #667eea; margin-bottom: 1.5rem; background: #f8f9fa; border-radius: 8px; }
        .required-mark { color: #dc3545; font-weight: bold; }
        .page { display: none; }
        .page.active { display: block; }
        
        /* Multiple Choice - Radio buttons vertical */
        .radio-group { display: flex; flex-direction: column; gap: 0.75rem; }
        .radio-group .form-check { margin-bottom: 0; }
        
        /* Checkbox - Grid layout */
        .checkbox-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .checkbox-grid .form-check { margin-bottom: 0; }

        /* Advance Checkbox - Table grid */
        .grid-table-wrapper { overflow-x: auto; border: 1px solid #dee2e6; border-radius: 8px; }
        .grid-table { min-width: 640px; margin-bottom: 0; }
        .grid-table th, .grid-table td { text-align: center; white-space: nowrap; padding: 10px; }
        .grid-row-label { text-align: left; background: #f8f9fa; font-weight: 600; }
        
        /* Rate Me - Horizontal radio grid */
        .rate-grid { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
        .rate-grid .form-check { margin-bottom: 0; }
        .rate-option-btn { cursor: pointer; padding: 0.5rem 1rem; border: 2px solid #dee2e6; border-radius: 6px; text-align: center; transition: all 0.2s; font-weight: 500; }
        .rate-option-btn:hover { border-color: #667eea; background: #f0f3ff; }
        .rate-option-btn.active { background: #667eea; color: white; border-color: #667eea; }
        
        .form-select { max-width: 100%; }
        .short-answer { max-width: 100%; }
        .paragraph-answer { max-width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Navigation buttons */
        .form-navigation { display: flex; gap: 1rem; justify-content: space-between; align-items: center; padding-top: 2rem; border-top: 1px solid #dee2e6; margin-top: 2rem; padding: 2rem; }
        .nav-buttons { display: flex; gap: 0.5rem; }
        .page-counter { color: #6c757d; font-size: 14px; white-space: nowrap; }
        
        /* Consent section */
        .consent-section { background: #f0f3ff; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea; margin-bottom: 1.5rem; }
        .consent-section h6 { margin-bottom: 1rem; color: #667eea; }
        .consent-terms { background: white; padding: 1rem; border-radius: 6px; max-height: 200px; overflow-y: auto; margin-bottom: 1rem; font-size: 13px; line-height: 1.6; }
        .email-hint-invalid { display: none; font-size: 12px; margin-top: 4px; }
        input[type="email"]:not(:placeholder-shown):invalid + .email-hint-invalid { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <div class="form-header">
                        <h2 class="mb-2"><i class="bi bi-file-earmark-text"></i> <?= esc($dcf['title']) ?></h2>
                        <?php if (empty($isPastDue)): ?>
                            <p class="mb-0"><?= esc($dcf['description']) ?></p>
                            <small><i class="bi bi-calendar-event"></i> Due: <?= esc($dcf['due_date']) ?></small>
                        <?php else: ?>
                            <p class="mb-0"><span class="badge bg-danger"><i class="bi bi-calendar2-x"></i> Submission Closed</span></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($isPastDue)): ?>
                        <div class="alert alert-danger rounded-0 mb-0 border-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            This form is past its due date and is no longer accepting responses.
                        </div>
                    <?php endif; ?>

                    <?php if (empty($isPastDue)): ?>

                    <div class="progress-indicator">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Progress</h6>
                            <span class="page-counter"><span id="currentPage">1</span> / <span id="totalPages">1</span></span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <form id="dcfResponseForm">
                        <!-- Page 1: User Information & Consent -->
                        <div class="page active" id="page-0">
                            <div class="page-content">
                                <h5 class="mb-4"><i class="bi bi-info-circle"></i> Your Information</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="required-mark">*</span></label>
                                    <input type="text" class="form-control" name="respondent_name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="required-mark">*</span></label>
                                    <input type="email" class="form-control" id="respondentEmail" name="respondent_email" placeholder="name@example.com" title="Please enter a valid email address" required>
                                    <small class="email-hint-invalid text-danger">Please enter a valid email address</small>
                                    <small class="text-muted d-block mt-1" id="emailRoleStatus"></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Role <span class="required-mark">*</span></label>
                                    <select class="form-select" id="respondentRole" name="respondent_role" required>
                                        <option value="">Select role</option>
                                        <?php foreach (($userRoles ?? []) as $role): ?>
                                            <?php $roleKey = trim((string) ($role['role_key'] ?? '')); ?>
                                            <?php if ($roleKey !== ''): ?>
                                                <option value="<?= esc($roleKey) ?>"><?= esc((string) ($role['role_name'] ?? $roleKey)) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if (!empty($jobTitles)): ?>
                                            <option disabled>─────────────────────</option>
                                            <option disabled>Azure Job Titles</option>
                                            <?php foreach ($jobTitles as $jobObj): ?>
                                                <?php $jobTitle = trim((string) ($jobObj['job_title'] ?? '')); ?>
                                                <?php if ($jobTitle !== ''): ?>
                                                    <option value="<?= esc($jobTitle) ?>"><?= esc($jobTitle) ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">If your email is registered, your role is auto-detected.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" name="respondent_mobile" placeholder="Optional">
                                </div>

                                <div class="consent-section">
                                    <h6><i class="bi bi-shield-check"></i> Terms & Conditions</h6>
                                    <div class="consent-terms">
                                        <p><strong>Data Privacy Notice</strong></p>
                                        <p>By submitting this form, you consent to the collection and processing of your personal information (name, email, and mobile number) for the purpose of recording your response to this survey. Your information will be handled in accordance with applicable data protection regulations.</p>
                                        <p>All responses are confidential and will be used only for the stated purpose. We will not share your personal information with third parties without your consent, except as required by law.</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="userConsent" name="user_consent" value="1" required>
                                        <label class="form-check-label" for="userConsent">
                                            I agree to the terms and conditions above <span class="required-mark">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pages for each part -->
                        <?php if (!empty($parts)): ?>
                            <?php foreach ($parts as $partIndex => $part): ?>
                                <?php $partRoleKey = trim((string) ($part['role_key'] ?? 'all')); ?>
                                <div class="page" id="page-<?= ($partIndex + 1) ?>">
                                    <div class="page-content">
                                        <div class="part-block mb-4" data-part-role="<?= esc($partRoleKey !== '' ? $partRoleKey : 'all') ?>">
                                            <h5 class="mb-2"><i class="bi bi-folder-check"></i> <?= esc($part['title'] ?? 'Part') ?></h5>
                                            <?php if (!empty($part['description'])): ?>
                                                <p class="text-muted"><?= esc($part['description']) ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <?php foreach (($part['questions'] ?? []) as $questionIndex => $question): ?>
                                            <?php $questionRoleKey = trim((string) ($question['role_key'] ?? '')); ?>
                                            <div class="question-block" data-question-role="<?= esc($questionRoleKey) ?>" data-part-role="<?= esc($partRoleKey !== '' ? $partRoleKey : 'all') ?>">
                                                <label class="form-label fw-bold">
                                                    <?= ($questionIndex + 1) ?>. <?= esc($question['question_text']) ?>
                                                    <?php if (!empty($question['is_required'])): ?>
                                                        <span class="required-mark">*</span>
                                                    <?php endif; ?>
                                                </label>

                                                <?php if ($question['answer_type'] === 'multiple_choice'): ?>
                                                    <div class="radio-group mt-2">
                                                        <?php foreach (($question['options'] ?? []) as $option): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" id="opt_<?= $question['id'] ?>_<?= md5($option['option_text']) ?>"
                                                                       name="answers[<?= $question['id'] ?>]"
                                                                       value="<?= esc($option['option_text']) ?>"
                                                                       <?= !empty($question['is_required']) ? 'required' : '' ?>>
                                                                <label class="form-check-label" for="opt_<?= $question['id'] ?>_<?= md5($option['option_text']) ?>">
                                                                    <?= esc($option['option_text']) ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php elseif ($question['answer_type'] === 'checkbox'): ?>
                                                    <div class="checkbox-grid mt-2">
                                                        <?php foreach (($question['options'] ?? []) as $option): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="chk_<?= $question['id'] ?>_<?= md5($option['option_text']) ?>"
                                                                       name="answers[<?= $question['id'] ?>][]"
                                                                       value="<?= esc($option['option_text']) ?>">
                                                                <label class="form-check-label" for="chk_<?= $question['id'] ?>_<?= md5($option['option_text']) ?>">
                                                                    <?= esc($option['option_text']) ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php elseif ($question['answer_type'] === 'advance_checkbox'): ?>
                                                    <?php
                                                        $gridRows = json_decode($question['grid_rows'] ?? '[]', true);
                                                        $gridColumns = json_decode($question['grid_columns'] ?? '[]', true);
                                                        if (!is_array($gridRows)) {
                                                            $gridRows = preg_split('/\r\n|\r|\n/', (string) ($question['grid_rows'] ?? ''));
                                                        }
                                                        if (!is_array($gridColumns)) {
                                                            $gridColumns = preg_split('/\r\n|\r|\n/', (string) ($question['grid_columns'] ?? ''));
                                                        }
                                                        $gridRows = array_values(array_filter(array_map('trim', $gridRows), 'strlen'));
                                                        $gridColumns = array_values(array_filter(array_map('trim', $gridColumns), 'strlen'));
                                                    ?>
                                                    <?php if (count($gridRows) > 0 && count($gridColumns) > 0): ?>
                                                        <div class="grid-table-wrapper mt-2">
                                                            <table class="table table-bordered grid-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <?php foreach ($gridColumns as $col): ?>
                                                                            <th><?= esc($col) ?></th>
                                                                        <?php endforeach; ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($gridRows as $rowIndex => $rowLabel): ?>
                                                                        <tr>
                                                                            <td class="grid-row-label"><?= esc($rowLabel) ?></td>
                                                                            <?php foreach ($gridColumns as $col): ?>
                                                                                <td>
                                                                                    <input class="form-check-input" type="checkbox"
                                                                                           name="answers[<?= $question['id'] ?>][<?= $rowIndex ?>][]"
                                                                                           value="<?= esc($col) ?>">
                                                                                </td>
                                                                            <?php endforeach; ?>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Grid rows and columns are not configured.</p>
                                                    <?php endif; ?>

                                                <?php elseif ($question['answer_type'] === 'dropdown'): ?>
                                                    <select class="form-select mt-2" name="answers[<?= $question['id'] ?>]" <?= !empty($question['is_required']) ? 'required' : '' ?>>
                                                        <option value="">-- Select an option --</option>
                                                        <?php foreach (($question['options'] ?? []) as $option): ?>
                                                            <option value="<?= esc($option['option_text']) ?>"><?= esc($option['option_text']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                <?php elseif ($question['answer_type'] === 'rate_me'): ?>
                                                    <?php
                                                        $rateMin = isset($question['rate_min']) && is_numeric($question['rate_min']) ? (int) $question['rate_min'] : 1;
                                                        $rateMax = isset($question['rate_max']) && is_numeric($question['rate_max']) ? (int) $question['rate_max'] : 10;
                                                        if ($rateMin < 1 || $rateMin > $rateMax) {
                                                            $rateMin = 1;
                                                            $rateMax = 10;
                                                        }
                                                    ?>
                                                    <div class="rate-grid mt-2" id="rateGrid_<?= $question['id'] ?>">
                                                        <?php for ($rating = $rateMin; $rating <= $rateMax; $rating++): ?>
                                                            <label class="rate-option-btn" onclick="this.querySelector('input').click()">
                                                                <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= $rating ?>" 
                                                                       style="display: none;" 
                                                                       onchange="updateRateButtons('rateGrid_<?= $question['id'] ?>')"
                                                                       <?= !empty($question['is_required']) ? 'required' : '' ?>>
                                                                <span><?= $rating ?></span>
                                                            </label>
                                                        <?php endfor; ?>
                                                    </div>

                                                <?php elseif ($question['answer_type'] === 'short_answer'): ?>
                                                    <input type="text" class="form-control short-answer mt-2" name="answers[<?= $question['id'] ?>]" placeholder="Enter your answer here..." <?= !empty($question['is_required']) ? 'required' : '' ?>>

                                                <?php elseif ($question['answer_type'] === 'wysiwyg'): ?>
                                                    <textarea class="form-control paragraph-answer mt-2 wysiwyg-answer" id="wysiwyg_<?= $question['id'] ?>" rows="6" name="answers[<?= $question['id'] ?>]" placeholder="Enter your response..." <?= !empty($question['is_required']) ? 'required' : '' ?>></textarea>

                                                <?php elseif ($question['answer_type'] === 'paragraph'): ?>
                                                    <textarea class="form-control paragraph-answer mt-2" rows="5" name="answers[<?= $question['id'] ?>]" placeholder="Enter your detailed answer here..." <?= !empty($question['is_required']) ? 'required' : '' ?>></textarea>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Navigation and Messages -->
                        <div class="form-navigation">
                            <div class="nav-buttons">
                                <button type="button" class="btn btn-secondary" id="backBtn" style="display: none;">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                            </div>
                            <button type="button" class="btn btn-primary" id="nextBtn">
                                <i class="bi bi-arrow-right"></i> Start Answering
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="bi bi-send"></i> Submit
                            </button>
                        </div>
                    </form>

                    <div id="responseMessage" class="alert mt-3" style="display: none;"></div>
                    <div id="submittedState" class="alert alert-success mt-3" style="display: none;">
                        <div style="text-align: center; padding: 2rem;">
                            <i class="bi bi-check-circle" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem; display: block;"></i>
                            <h5>Form Submitted Successfully!</h5>
                            <p class="text-muted">Thank you for completing the form. Your response has been recorded.</p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="page-content">
                        <div class="alert alert-warning mb-0">
                            <h6 class="mb-2"><i class="bi bi-calendar2-x"></i> Submission Closed</h6>
                            <p class="mb-0">This DCF form already passed its due date and no longer accepts responses.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.8.0/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        const ANSWER_TYPES_WITH_OPTIONS = ['multiple_choice', 'checkbox', 'dropdown'];
        const form = document.getElementById('dcfResponseForm');
        const respondentEmailInput = document.getElementById('respondentEmail');
        const respondentRoleSelect = document.getElementById('respondentRole');
        const emailRoleStatus = document.getElementById('emailRoleStatus');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('progressBar');
        const currentPageSpan = document.getElementById('currentPage');
        const totalPagesSpan = document.getElementById('totalPages');
        const isPastDue = <?= !empty($isPastDue) ? 'true' : 'false' ?>;
        const emailRoleLookupUrl = '<?= site_url('dcf/email-role/' . $dcf['share_token']) ?>';
        let emailLookupTimer = null;
        let detectedRoleKeys = [];

        function setEmailRoleStatus(message, cssClass = 'text-muted') {
            if (!emailRoleStatus) {
                return;
            }

            emailRoleStatus.className = `${cssClass} d-block mt-1`;
            emailRoleStatus.textContent = message;
        }

        function ensureRoleOption(roleKey) {
            if (!respondentRoleSelect || !roleKey) {
                return;
            }

            const existing = Array.from(respondentRoleSelect.options).find(option => option.value === roleKey);
            if (existing) {
                return;
            }

            const option = document.createElement('option');
            option.value = roleKey;
            option.textContent = roleKey;
            respondentRoleSelect.appendChild(option);
        }

        function setRoleLocked(locked) {
            if (!respondentRoleSelect) {
                return;
            }

            respondentRoleSelect.disabled = !!locked;
            respondentRoleSelect.classList.toggle('bg-light', !!locked);
            respondentRoleSelect.title = locked
                ? 'Role is locked because this email is registered.'
                : '';
        }

        async function lookupEmailRole(emailValue) {
            const email = String(emailValue || '').trim().toLowerCase();
            if (!email || !respondentRoleSelect) {
                setEmailRoleStatus('', 'text-muted');
                setRoleLocked(false);
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                setEmailRoleStatus('', 'text-muted');
                setRoleLocked(false);
                return;
            }

            setEmailRoleStatus('Checking registered email...', 'text-muted');

            try {
                const response = await fetch(`${emailRoleLookupUrl}?email=${encodeURIComponent(email)}`);
                const result = await response.json();

                if (!result || result.success !== true) {
                    setEmailRoleStatus('Unable to verify email right now.', 'text-warning');
                    detectedRoleKeys = [];
                    setRoleLocked(false);
                    return;
                }

                if (result.registered && result.role_key) {
                    const serverRoleKeys = Array.isArray(result.role_keys) ? result.role_keys : [];
                    detectedRoleKeys = serverRoleKeys
                        .map(value => String(value || '').trim())
                        .filter(value => value !== '');

                    if (detectedRoleKeys.length === 0) {
                        detectedRoleKeys = [String(result.role_key || '').trim()].filter(value => value !== '');
                    }

                    detectedRoleKeys.forEach((roleKey) => ensureRoleOption(roleKey));
                    ensureRoleOption(result.role_key);
                    respondentRoleSelect.value = result.role_key;
                    setRoleLocked(true);
                    setEmailRoleStatus(`Registered email detected. Role/Job Title auto-selected: ${result.role_key}`, 'text-success');
                    applyRoleScope();
                } else if (result.registered) {
                    detectedRoleKeys = [];
                    setRoleLocked(false);
                    setEmailRoleStatus('Registered email found, but no role is assigned yet. Please select your role.', 'text-warning');
                    applyRoleScope();
                } else {
                    detectedRoleKeys = [];
                    setRoleLocked(false);
                    setEmailRoleStatus('Email is not registered. Please select your role.', 'text-warning');
                    applyRoleScope();
                }
            } catch (error) {
                setEmailRoleStatus('Unable to verify email right now.', 'text-warning');
                detectedRoleKeys = [];
                setRoleLocked(false);
            }
        }

        function resolveEffectiveTargetRole(questionRole, partRole) {
            const qRole = String(questionRole || '').trim();
            if (qRole !== '') {
                return qRole;
            }

            const pRole = String(partRole || '').trim();
            return pRole !== '' ? pRole : 'all';
        }

        function normalizeRoleKey(value) {
            const text = String(value || '').trim();
            if (!text) {
                return '';
            }

            return text.replace(/\s+/g, ' ').toLowerCase();
        }

        function roleKeysMatch(leftRoleKey, rightRoleKey) {
            const left = normalizeRoleKey(leftRoleKey);
            const right = normalizeRoleKey(rightRoleKey);

            if (!left || !right) {
                return false;
            }

            if (left === right) {
                return true;
            }

            if (canonicalizeRoleKey(left) === canonicalizeRoleKey(right)) {
                return true;
            }

            const shorterLength = Math.min(left.length, right.length);
            if (shorterLength < 45) {
                return false;
            }

            return left.startsWith(right) || right.startsWith(left);
        }

        function canonicalizeRoleKey(value) {
            const normalized = normalizeRoleKey(value);
            if (!normalized) {
                return '';
            }

            const collapsed = normalized
                .replace(/[^a-z0-9]+/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();

            if (!collapsed) {
                return '';
            }

            return collapsed
                .split(' ')
                .map((token) => {
                    if (token.length > 3 && token.endsWith('s')) {
                        return token.slice(0, -1);
                    }
                    return token;
                })
                .filter(Boolean)
                .join(' ');
        }

        function canAccessByRole(targetRole, selectedRoles = []) {
            const target = normalizeRoleKey(targetRole);
            const normalizedSelectedRoles = (Array.isArray(selectedRoles) ? selectedRoles : [selectedRoles])
                .map((value) => normalizeRoleKey(value))
                .filter((value) => value !== '');

            if (target === '' || target === 'all') {
                return true;
            }

            if (normalizedSelectedRoles.length === 0 || normalizedSelectedRoles.includes('all')) {
                return true;
            }

            return normalizedSelectedRoles.some((selectedRole) => roleKeysMatch(target, selectedRole));
        }

        function applyRoleScope() {
            const selectedRole = respondentRoleSelect ? respondentRoleSelect.value : '';
            const roleCandidates = [];
            if (selectedRole && String(selectedRole).trim() !== '') {
                roleCandidates.push(selectedRole);
            }

            if (Array.isArray(detectedRoleKeys)) {
                detectedRoleKeys.forEach((roleKey) => {
                    if (String(roleKey || '').trim() !== '') {
                        roleCandidates.push(roleKey);
                    }
                });
            }

            const questionBlocks = document.querySelectorAll('.question-block');

            questionBlocks.forEach((block) => {
                const questionRole = block.getAttribute('data-question-role') || '';
                const partRole = block.getAttribute('data-part-role') || 'all';
                const targetRole = resolveEffectiveTargetRole(questionRole, partRole);
                const accessible = canAccessByRole(targetRole, roleCandidates);

                block.style.display = accessible ? '' : 'none';

                const fields = block.querySelectorAll('input, select, textarea');
                fields.forEach((field) => {
                    if (!field.dataset.requiredOriginal) {
                        field.dataset.requiredOriginal = field.hasAttribute('required') ? '1' : '0';
                    }

                    field.disabled = !accessible;

                    if (accessible) {
                        if (field.dataset.requiredOriginal === '1') {
                            field.setAttribute('required', 'required');
                        }
                    } else {
                        field.removeAttribute('required');
                    }
                });
            });
        }

        if (respondentEmailInput) {
            respondentEmailInput.addEventListener('input', () => {
                if (emailLookupTimer) {
                    clearTimeout(emailLookupTimer);
                }

                emailLookupTimer = setTimeout(() => {
                    lookupEmailRole(respondentEmailInput.value);
                }, 300);
            });
        }

        if (respondentRoleSelect) {
            respondentRoleSelect.addEventListener('change', () => {
                applyRoleScope();
            });
        }
        
        // Calculate total pages (1 for info + consent, then 1 per part)
        const totalPages = <?= (1 + count($parts ?? [])) ?>;
        if (totalPagesSpan) {
            totalPagesSpan.textContent = totalPages;
        }
        
        let currentPage = 0;

        function initWysiwygEditors() {
            if (!window.tinymce) {
                return;
            }

            tinymce.init({
                selector: '.wysiwyg-answer',
                height: 300,
                menubar: 'file edit view insert format tools table help',
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount help',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table media | removeformat | code fullscreen preview',
                toolbar_mode: 'wrap',
                branding: false,
                promotion: false,
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
                
                // Prevent TinyMCE from converting absolute URLs to relative paths
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                
                // Enable image upload
                images_upload_url: '<?= site_url('dcf/upload-image') ?>',
                automatic_uploads: true,
                images_reuse_filename: true,
                
                // File picker for images
                file_picker_types: 'image',
                file_picker_callback: function (callback, value, meta) {
                    if (meta.filetype === 'image') {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        
                        input.onchange = function () {
                            const file = this.files[0];
                            const reader = new FileReader();
                            
                            reader.onload = function () {
                                const id = 'blobid' + (new Date()).getTime();
                                const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                const base64 = reader.result.split(',')[1];
                                const blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                
                                callback(blobInfo.blobUri(), { title: file.name });
                            };
                            
                            reader.readAsDataURL(file);
                        };
                        
                        input.click();
                    }
                },
                
                // Handle image upload
                images_upload_handler: function (blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '<?= site_url('dcf/upload-image') ?>');
                        
                        xhr.upload.onprogress = (e) => {
                            progress(e.loaded / e.total * 100);
                        };
                        
                        xhr.onload = () => {
                            if (xhr.status === 403) {
                                reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                return;
                            }
                            
                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject('HTTP Error: ' + xhr.status);
                                return;
                            }
                            
                            const json = JSON.parse(xhr.responseText);
                            
                            if (!json || typeof json.location != 'string') {
                                reject('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            
                            resolve(json.location);
                        };
                        
                        xhr.onerror = () => {
                            reject('Image upload failed due to an XHR Transport error. Code: ' + xhr.status);
                        };
                        
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                        
                        xhr.send(formData);
                    });
                },
                
                setup: (editor) => {
                    editor.on('change keyup', () => {
                        editor.save();
                    });
                }
            });
        }

        function getWysiwygText(textarea) {
            if (window.tinymce) {
                const editor = tinymce.get(textarea.id);
                if (editor) {
                    return editor.getContent({ format: 'text' }).trim();
                }
            }
            return textarea.value.trim();
        }

        // Update rate button styling
        function updateRateButtons(gridId) {
            const grid = document.getElementById(gridId);
            const buttons = grid.querySelectorAll('.rate-option-btn');
            buttons.forEach(btn => {
                const input = btn.querySelector('input');
                if (input.checked) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Show specific page
        function showPage(pageNum) {
            if (!progressBar || !currentPageSpan || !nextBtn || !backBtn || !submitBtn) {
                return;
            }

            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Show current page
            const activePage = document.getElementById(`page-${pageNum}`);
            if (!activePage) {
                return;
            }
            activePage.classList.add('active');
            
            // Update button visibility and text
            backBtn.style.display = pageNum === 0 ? 'none' : 'block';
            
            if (pageNum === 0) {
                nextBtn.innerHTML = '<i class="bi bi-arrow-right"></i> Start Answering';
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
            } else if (pageNum === totalPages - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = isPastDue ? 'none' : 'block';
            } else {
                nextBtn.innerHTML = '<i class="bi bi-arrow-right"></i> Next';
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
            }
            
            // Update progress bar and counter
            const progress = ((pageNum + 1) / totalPages) * 100;
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', Math.round(progress));
            currentPageSpan.textContent = pageNum + 1;
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Validate current page
        function validateCurrentPage() {
            const page = document.getElementById(`page-${currentPage}`);
            if (!page) {
                return false;
            }
            const wysiwygRequired = page.querySelectorAll('textarea.wysiwyg-answer[required]');
            wysiwygRequired.forEach(textarea => {
                const hasContent = getWysiwygText(textarea) !== '';
                textarea.setCustomValidity(hasContent ? '' : 'Please fill out this field.');
            });

            if (window.tinymce) {
                tinymce.triggerSave();
            }

            const inputs = page.querySelectorAll('input[required], textarea[required], select[required]');
            
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        // Next button click
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (validateCurrentPage()) {
                    if (currentPage < totalPages - 1) {
                        currentPage++;
                        showPage(currentPage);
                    }
                }
            });
        }

        // Back button click
        if (backBtn) {
            backBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage > 0) {
                    currentPage--;
                    showPage(currentPage);
                }
            });
        }

        // Form submission
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (isPastDue) {
                    const messageDiv = document.getElementById('responseMessage');
                    if (messageDiv) {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> This form is past its due date and is no longer accepting responses.';
                        messageDiv.style.display = 'block';
                    }
                    return;
                }

                if (!validateCurrentPage()) {
                    return;
                }

            if (window.tinymce) {
                tinymce.triggerSave();
            }

                const formData = new FormData(this);
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';

            try {
                    const response = await fetch('<?= base_url('dcf/submit/' . $dcf['share_token']) ?>', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    const messageDiv = document.getElementById('responseMessage');

                    if (result.success) {
                        form.style.display = 'none';
                        const navigation = document.querySelector('.form-navigation');
                        if (navigation) {
                            navigation.style.display = 'none';
                        }
                        const indicator = document.querySelector('.progress-indicator');
                        if (indicator) {
                            indicator.style.display = 'none';
                        }
                        if (messageDiv) {
                            messageDiv.style.display = 'none';
                        }
                        const submittedState = document.getElementById('submittedState');
                        if (submittedState) {
                            submittedState.style.display = 'block';
                        }
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        if (messageDiv) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + (result.message || 'An error occurred');
                            messageDiv.style.display = 'block';
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (error) {
                    const messageDiv = document.getElementById('responseMessage');
                    if (messageDiv) {
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> An error occurred. Please try again.';
                        messageDiv.style.display = 'block';
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // Initialize
        if (!isPastDue && form) {
            initWysiwygEditors();
            showPage(0);
            applyRoleScope();

            if (respondentEmailInput && respondentEmailInput.value.trim() !== '') {
                lookupEmailRole(respondentEmailInput.value);
            }
        }
    </script>
</body>
</html>

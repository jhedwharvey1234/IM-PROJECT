<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($dcf['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem 0; }
        .form-container { background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 12px 12px 0 0; }
        .part-block { padding: 1rem; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 1rem; background: #ffffff; }
        .question-block { padding: 1rem; border-left: 4px solid #667eea; margin-bottom: 1rem; background: #f8f9fa; border-radius: 8px; }
        .required-mark { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <div class="form-header">
                        <h2 class="mb-2"><i class="bi bi-file-earmark-text"></i> <?= esc($dcf['title']) ?></h2>
                        <p class="mb-0"><?= esc($dcf['description']) ?></p>
                        <small><i class="bi bi-calendar-event"></i> Due: <?= esc($dcf['due_date']) ?></small>
                    </div>

                    <div class="p-4">
                        <form id="dcfResponseForm">
                            <div class="mb-4 border-bottom pb-4">
                                <h5 class="mb-3">Your Information</h5>
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="required-mark">*</span></label>
                                    <input type="text" class="form-control" name="respondent_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" name="respondent_mobile">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="required-mark">*</span></label>
                                    <input type="email" class="form-control" name="respondent_email" required>
                                </div>
                            </div>

                            <h5 class="mb-3">Parts and Questions</h5>

                            <?php if (!empty($parts)): ?>
                                <?php foreach ($parts as $partIndex => $part): ?>
                                    <div class="part-block">
                                        <h6 class="mb-1"><?= ($partIndex + 1) ?>. <?= esc($part['title'] ?? 'Part') ?></h6>
                                        <?php if (!empty($part['description'])): ?>
                                            <p class="text-muted mb-3"><?= esc($part['description']) ?></p>
                                        <?php endif; ?>

                                        <?php foreach (($part['questions'] ?? []) as $questionIndex => $question): ?>
                                            <div class="question-block">
                                                <label class="form-label fw-bold">
                                                    <?= ($questionIndex + 1) ?>. <?= esc($question['question_text']) ?>
                                                    <?php if (!empty($question['is_required'])): ?>
                                                        <span class="required-mark">*</span>
                                                    <?php endif; ?>
                                                </label>

                                                <?php if ($question['answer_type'] === 'multiple_choice'): ?>
                                                    <?php foreach (($question['options'] ?? []) as $option): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                   name="answers[<?= $question['id'] ?>]"
                                                                   value="<?= esc($option['option_text']) ?>"
                                                                   <?= !empty($question['is_required']) ? 'required' : '' ?>>
                                                            <label class="form-check-label"><?= esc($option['option_text']) ?></label>
                                                        </div>
                                                    <?php endforeach; ?>

                                                <?php elseif ($question['answer_type'] === 'checkbox'): ?>
                                                    <?php foreach (($question['options'] ?? []) as $option): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="answers[<?= $question['id'] ?>][]"
                                                                   value="<?= esc($option['option_text']) ?>">
                                                            <label class="form-check-label"><?= esc($option['option_text']) ?></label>
                                                        </div>
                                                    <?php endforeach; ?>

                                                <?php elseif ($question['answer_type'] === 'dropdown'): ?>
                                                    <select class="form-select" name="answers[<?= $question['id'] ?>]" <?= !empty($question['is_required']) ? 'required' : '' ?>>
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
                                                    <select class="form-select" name="answers[<?= $question['id'] ?>]" <?= !empty($question['is_required']) ? 'required' : '' ?>>
                                                        <option value="">-- Rate from <?= $rateMin ?> to <?= $rateMax ?> --</option>
                                                        <?php for ($rating = $rateMin; $rating <= $rateMax; $rating++): ?>
                                                            <option value="<?= $rating ?>"><?= $rating ?></option>
                                                        <?php endfor; ?>
                                                    </select>

                                                <?php elseif ($question['answer_type'] === 'short_answer'): ?>
                                                    <input type="text" class="form-control" name="answers[<?= $question['id'] ?>]" <?= !empty($question['is_required']) ? 'required' : '' ?>>

                                                <?php elseif ($question['answer_type'] === 'paragraph'): ?>
                                                    <textarea class="form-control" rows="4" name="answers[<?= $question['id'] ?>]" <?= !empty($question['is_required']) ? 'required' : '' ?>></textarea>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No questions available for this form.</p>
                            <?php endif; ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send"></i> Submit Response
                                </button>
                            </div>
                        </form>

                        <div id="responseMessage" class="alert mt-3" style="display: none;"></div>
                        <div id="submittedState" class="alert alert-success mt-3" style="display: none;">
                            <i class="bi bi-check-circle"></i> Form submitted successfully.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('dcfResponseForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';

            try {
                const response = await fetch('<?= base_url('dcf/submit/' . $dcf['share_token']) ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                const messageDiv = document.getElementById('responseMessage');

                if (result.success) {
                    const submittedState = document.getElementById('submittedState');
                    this.style.display = 'none';
                    messageDiv.style.display = 'none';
                    submittedState.style.display = 'block';
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + result.message;
                    messageDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                const messageDiv = document.getElementById('responseMessage');
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> An error occurred. Please try again.';
                messageDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>

# DCF Management API Endpoints & Routes

## 🔒 Admin Routes (Requires Superadmin Auth)

### DCF CRUD Operations
```
GET  /dcf                      → List all DCFs with filters
GET  /dcf/create               → Show DCF creation form
POST /dcf/store                → Save new DCF (with questions)
GET  /dcf/edit/:id             → Show DCF edit form
POST /dcf/update/:id           → Update existing DCF
GET  /dcf/delete/:id           → Delete DCF (cascades to questions/responses)
```

### DCF Details & Analytics
```
GET  /dcf/details/:id          → View DCF details with Info & Results tabs
                                 - Displays QR code
                                 - Shows shareable link
                                 - Lists all responses
                                 - Shows analytics charts
```

### Questions Management
```
GET  /dcf/questions            → List all past questions across DCFs
GET  /dcf/past-questions/:departmentId  → Get questions by department (JSON API)
                                          Used by Past Questions modal
```

---

## 🌐 Public Routes (No Authentication)

### Response Submission
```
GET  /dcf/form/:shareToken     → Public answering page
                                 - Display questions
                                 - Collect respondent info
                                 - No login required

POST /dcf/submit/:shareToken   → Submit response (AJAX)
                                 - Validates required fields
                                 - Saves to dcf_responses
                                 - Returns JSON success/error
```

**Example Token:**
```
a1b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef123456
```

---

## 📊 Data Flow

### Creating a DCF:
```
POST /dcf/store
↓
Request Body:
{
  "title": "Customer Satisfaction Survey",
  "description": "Please rate your experience",
  "due_date": "2024-12-31",
  "department_id": 5,
  "questions": [
    {
      "question_text": "How satisfied are you?",
      "is_required": 1,
      "answer_type": "multiple_choice",
      "options": ["Very Satisfied", "Satisfied", "Neutral", "Dissatisfied"]
    }
  ]
}
↓
Database Actions:
1. INSERT INTO dcfs (auto-generates share_token)
2. INSERT INTO dcf_questions
3. INSERT INTO dcf_question_options
4. COMMIT transaction
↓
Response:
Redirect to /dcf with success message
```

### Submitting a Response:
```
POST /dcf/submit/:shareToken
↓
Request Body (FormData):
{
  "respondent_name": "John Doe",
  "respondent_mobile": "09171234567",
  "respondent_email": "john@example.com",
  "answers": {
    "1": "Very Satisfied",           // Multiple choice
    "2": ["Feature A", "Feature B"], // Checkbox
    "3": "Great service!",           // Short answer
    "4": "I really enjoyed..."       // Paragraph
  }
}
↓
Validation:
- Check share_token exists
- Validate respondent_name (required)
- Validate email format (if provided)
- Check required questions answered
↓
Database Actions:
1. INSERT INTO dcf_responses
2. INSERT INTO dcf_response_answers (for each question)
3. JSON encode checkbox arrays
4. COMMIT transaction
↓
Response:
{
  "success": true,
  "message": "Thank you for your response!"
}
```

---

## 🗄️ Database Tables Quick Reference

### dcfs
```
id (PK) | title | description | due_date | department_id (FK) | share_token (UK) | created_at | updated_at
```

### dcf_questions
```
id (PK) | dcf_id (FK) | question_text | is_required | answer_type (ENUM) | sort_order
```
**Answer Types:** `multiple_choice`, `checkbox`, `dropdown`, `short_answer`, `paragraph`

### dcf_question_options
```
id (PK) | question_id (FK) | option_text | sort_order
```

### dcf_responses
```
id (PK) | dcf_id (FK) | respondent_name | respondent_mobile | respondent_email | submitted_at | ip_address | user_agent
```

### dcf_response_answers
```
id (PK) | response_id (FK) | question_id (FK) | answer_text | created_at
```
**Note:** Checkbox answers stored as JSON: `["Option 1", "Option 2"]`

---

## 🔄 Controller Methods Reference

### DcfController.php
| Method | Route | Purpose |
|--------|-------|---------|
| `index()` | GET /dcf | List all DCFs with department join |
| `create()` | GET /dcf/create | Show creation form with departments |
| `store()` | POST /dcf/store | Create DCF with questions (transaction) |
| `edit($id)` | GET /dcf/edit/:id | Load DCF with questions for editing |
| `update($id)` | POST /dcf/update/:id | Update DCF and replace questions |
| `delete($id)` | GET /dcf/delete/:id | Delete DCF (cascade to responses) |
| `questions()` | GET /dcf/questions | List all questions across DCFs |
| `pastQuestionsByDepartment($id)` | GET /dcf/past-questions/:id | JSON API for question reuse |
| `details($id)` | GET /dcf/details/:id | Show Info & Results tabs |

### DcfPublicController.php
| Method | Route | Purpose |
|--------|-------|---------|
| `form($token)` | GET /dcf/form/:token | Display public answering form |
| `submit($token)` | POST /dcf/submit/:token | Process and save response |

---

## 🎯 Key Features by Route

### `/dcf/details/:id` (Info Tab)
- ✅ DCF metadata display (title, description, due date, department)
- ✅ Question list with types and options
- ✅ QR code generation (qrcodejs)
- ✅ Shareable link with copy button
- ✅ Total response count stat

### `/dcf/details/:id` (Results Tab)
- ✅ Response count statistics
- ✅ Respondent table (name, mobile, email, timestamp)
- ✅ Question-by-question analytics:
  - Multiple Choice/Dropdown: Frequency counts + percentage bars
  - Checkbox: Aggregated option counts
  - Short Answer/Paragraph: Full text list
- ✅ Empty state when no responses

### `/dcf/form/:token` (Public Form)
- ✅ No authentication required
- ✅ Beautiful gradient UI
- ✅ Respondent info collection (name required)
- ✅ Dynamic question rendering (radio, checkbox, dropdown, input, textarea)
- ✅ Client-side validation
- ✅ AJAX submission with loading state
- ✅ Success/error messages

---

## 🔐 Security & Validation

### Share Token Generation
```php
// In Dcf model beforeInsert callback:
$data['data']['share_token'] = bin2hex(random_bytes(32)); // 64 hex chars
```

### Public Form Validation Rules
```php
'respondent_name' => 'required|max_length[255]'
'respondent_mobile' => 'permit_empty|max_length[50]'
'respondent_email' => 'permit_empty|valid_email|max_length[255]'
```

### Admin Route Protection
```php
if (!session()->get('user_id') || session()->get('usertype') !== 'superadmin') {
    return redirect()->to('dashboard')->with('error', 'Unauthorized access');
}
```

---

## 📈 Analytics Aggregation Logic

### Multiple Choice / Dropdown
```php
$counts = [];
foreach ($answers as $ans) {
    $counts[$ans['answer_text']]++;
}
arsort($counts); // Sort by frequency
// Output: ["Option A" => 10, "Option B" => 5]
```

### Checkbox (Multi-select)
```php
$counts = [];
foreach ($answers as $ans) {
    $decoded = json_decode($ans['answer_text'], true);
    foreach ($decoded as $item) {
        $counts[$item]++;
    }
}
arsort($counts);
// Output: ["Feature 1" => 8, "Feature 2" => 6]
```

### Short Answer / Paragraph
```php
$textList = array_map(fn($a) => $a['answer_text'], $answers);
// Output: ["Response 1", "Response 2", ...]
```

---

## 🛠️ Common Tasks

### Get Shareable Link for DCF
```php
$dcf = $dcfModel->find($id);
$shareUrl = base_url('dcf/form/' . $dcf['share_token']);
```

### Count Responses for DCF
```php
$responseModel = new DcfResponse();
$count = $responseModel->where('dcf_id', $id)->countAllResults();
```

### Get All Answers for a Question
```php
$answerModel = new DcfResponseAnswer();
$answers = $answerModel
    ->select('dcf_response_answers.answer_text')
    ->join('dcf_responses', 'dcf_responses.id = dcf_response_answers.response_id')
    ->where('dcf_responses.dcf_id', $dcfId)
    ->where('dcf_response_answers.question_id', $questionId)
    ->findAll();
```

### Check if Share Token Exists
```php
$dcf = $dcfModel->where('share_token', $token)->first();
if (!$dcf) {
    // Invalid or expired link
}
```

---

## 📱 Frontend Libraries

### Admin Pages
- Bootstrap 5.3.0
- Bootstrap Icons 1.11.3
- qrcodejs 1.0.0 (CDN)

### Public Form
- Bootstrap 5.3.0
- Bootstrap Icons 1.10.0
- Fetch API (native)

---

## 🎨 CSS Classes Reference

### Stat Boxes
```css
.stat-box - Gradient background stat card
```

### Share Components
```css
.share-box - Dashed border container for QR/link
#qrcode - QR code container
```

### Question Blocks (Public Form)
```css
.question-block - Left-border accent card
.required-mark - Red asterisk for required fields
```

### Analytics
```css
.chart-container - White card for analytics
.progress-bar - Bootstrap percentage bars
```

---

## 🔍 Testing Commands

### Check Tables
```sql
SHOW TABLES LIKE 'dcf_%';
DESCRIBE dcfs;
DESCRIBE dcf_responses;
```

### View Sample Data
```sql
SELECT id, title, share_token FROM dcfs;
SELECT * FROM dcf_responses WHERE dcf_id = 1;
SELECT * FROM dcf_response_answers WHERE response_id = 1;
```

### Test Share Token Generation
```php
// In tinker or test script:
$dcf = new \App\Models\Dcf();
$dcf->insert(['title' => 'Test', 'department_id' => 1, 'due_date' => '2024-12-31']);
// Should auto-generate share_token
```

---

## 📞 Debug Checklist

**Issue:** Form not loading
- [ ] Check share_token exists in database
- [ ] Verify route registered: `php spark routes | grep dcf/form`
- [ ] Check DcfPublicController exists

**Issue:** Response not saving
- [ ] Check form validation errors in console
- [ ] Verify tables exist: `dcf_responses`, `dcf_response_answers`
- [ ] Check foreign key constraints

**Issue:** QR code not showing
- [ ] Check internet connection (CDN loading)
- [ ] Verify qrcodejs script loaded (browser dev tools)
- [ ] Inspect #qrcode element

**Issue:** Analytics empty
- [ ] Check response count > 0
- [ ] Verify answers linked to questions correctly
- [ ] Check JOIN queries in details() method

---

**Last Updated:** December 2024  
**Version:** 1.0.0  
**Status:** Production Ready ✅

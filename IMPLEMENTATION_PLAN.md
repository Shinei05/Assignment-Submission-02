# Implementation Plan

## Functionality

### 1. Submission Content — File upload or text input mechanism for students to submit work

**Priority:** HIGH  
**Complexity:** MEDIUM

**Behavior:**
- [x] Define submission input method (file upload, text, or both) - File upload only.
- [x] Specify supported file types and size limits - PDF, EXCEL, DOCX, JSON, max 25 MB.
- [x] Determine if students can edit/resubmit before deadline - Before deadline: yes. After deadline: no.
- [x] Decide on submission confirmation flow - Show confirmation modal on submit; on confirm upload file, show success toast with submission timestamp, then return to task details page.

**Notes:**

---

### 2. File Download — Reference files downloadable; submission files retrievable

**Priority:** HIGH  
**Complexity:** MEDIUM

**Behavior:**
- [x] Define file storage location (public/private storage) - Public storage using Laravel Storage facade.
- [x] Specify download permissions (students download own submissions, teachers download all) - Yes.
- [x] Determine file access restrictions (after deadline, grade release, etc.) - None.
- [x] Define virus/malware scanning requirements (if any) - None.

**Notes:**

---

### 3. Search/Filter — Search in classes, tasks, and submissions

**Priority:** MEDIUM  
**Complexity:** LOW

**Behavior:**
- [x] Define search scope - Class name only.
- [x] Specify filter criteria for each entity (classes, tasks, submissions) - No extra filters for now.
- [x] Determine search performance requirements (pagination, lazy loading) - Use existing pagination.
- [x] Decide on search result ranking/sorting options - Default ordering only.

**Notes:**

---

### 4. Late Submission Handling — Logic for submissions after due date

**Priority:** MEDIUM  
**Complexity:** LOW

**Behavior:**
- [x] Define if late submissions are allowed or blocked - Allowed with `late` status.
- [x] Specify grace period (if any) after due date - None.
- [x] Determine late submission marking/flagging in UI - Prompt an informational modal indicating late submission.

**Notes:**

---

### 5. Class Roster Management — Remove/withdraw students from classes

**Priority:** MEDIUM  
**Complexity:** LOW

**Behavior:**
- [x] Define who can remove students (teachers only, admins, students) - Teacher only (class owner).
- [x] Specify what happens to student submissions when removed from class - Keep existing submissions as-is.
- [x] Decide on confirmation/warning before removal - Always show confirmation modal for major actions.

**Notes:**

---

## UI/UX

### 1. Submission Form — Where students input/upload work

**Priority:** HIGH  
**Complexity:** MEDIUM

**Behavior:**
- [x] Design form layout (inline editor, modal, dedicated page) - Drag-and-drop upload field on task submission view.
- [x] Specify form validation (required fields, file size, format checks) - File is required; allow only PDF, EXCEL, DOCX, JSON; max 25 MB.
- [x] Define success/error messaging - Use toast.
- [x] Specify if submission timestamp is shown - Yes.

**Notes:**


---

### 2. Profile/Settings — User profile and account management UI

**Priority:** MEDIUM  
**Complexity:** MEDIUM

**Behavior:**
- [ ] Define editable profile fields (name, email, photo, bio)
- [ ] Specify password change flow
- [ ] Determine notification preferences UI (if needed)
- [ ] Decide on account deactivation/deletion option
- [ ] Define role-specific profile pages (teacher vs student)

**Notes:**
- Deferred for later.

---

### 3. Responsive Mobile UI — Mobile-optimized templates

**Priority:** MEDIUM  
**Complexity:** MEDIUM

**Behavior:**
- [ ] Define mobile breakpoints and layout adjustments
- [ ] Specify touch-friendly button/input sizes
- [ ] Determine mobile-specific navigation (hamburger menu, bottom tabs, etc.)
- [ ] Define performance optimizations for mobile (lazy loading, compression)
- [ ] Specify testing devices and breakpoints

**Notes:**
- Deferred for later.

---

## Data/Integration

### 1. File Storage System — For submissions and reference materials

**Priority:** HIGH  
**Complexity:** HIGH

**Behavior:**
- [x] Choose storage driver (local disk, S3, cloud storage) - Local via Laravel Storage facade.
- [x] Define directory structure for files (organized by class/task/submission) - Proceed with this.

**Notes:**

---

## Summary

| Item | Priority | Complexity | Status |
|------|----------|-----------|--------|
| Submission Content | HIGH | MEDIUM | ⏳ |
| File Download | HIGH | MEDIUM | ⏳ |
| Search/Filter | MEDIUM | LOW | ⏳ |
| Late Submission Handling | MEDIUM | LOW | ⏳ |
| Class Roster Management | MEDIUM | LOW | ⏳ |
| Submission Form | HIGH | MEDIUM | ⏳ |
| Profile/Settings | MEDIUM | MEDIUM | ⏳ |
| Responsive Mobile UI | MEDIUM | MEDIUM | ⏳ |
| File Storage System | HIGH | HIGH | ⏳ |

---

## Status Legend
- ⏳ Not Started
- 🚀 In Progress
- ✅ Completed
- 🔧 In Review

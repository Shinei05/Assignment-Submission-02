# Application Re-creation Prompt & System Specifications

## Re-creation Prompt
**Objective:** Recreate the "Assignment Submission App", a web application for teachers and students to manage classes, tasks, and assignment submissions. 

**Instructions for AI:**
You are tasked with rebuilding this Laravel-based Assignment Submission application from scratch. You must follow the exact database schema, routing structure, and business logic listed below. **Crucially, you must strictly adhere to the Project Rules & Coding Standards** provided in this document. 

*UI/UX Note:* While the feature set, routing, and data flow are strictly defined, you have creative freedom regarding the color theme and overall UI layout. Ensure the design is **simple, clean, and modern**, utilizing the global styling rules (such as CSS variables and predefined components) outlined in the coding standards. Avoid inline styles, complex gradients, or overly cluttered interfaces. Use solid colors and maintain a clear typography hierarchy.

---

## 1. Application Features & Modules

### User Roles
- **Teacher:** Can manage classes, assign tasks, review and mark student submissions, and view a calendar of tasks.
- **Student:** Can view enrolled classes, access tasks, submit assignments (with file uploads), and view their task calendar.

### Routing & Controllers Flow
The application follows a strict request lifecycle: `Routes → Middleware → Controller → Service → Repository → Model`. Controllers must be thin and return JSON or views, delegating business logic to Service classes.

**Authentication & Common:**
- Base route `/` redirects to login.
- Profile management (Edit, Update, Destroy).

**Student Portal (`/student/*`):**
- **Dashboard:** Overview of pending tasks and recent activities.
- **Classes:** View enrolled classes (`/classes`) and class details (`/classes/{class}`).
- **Assignments:** 
  - List assignments (`/assignments`).
  - View task details within a class (`/classes/{class}/tasks/{task}`) or globally (`/assignments/{taskId}`).
  - Submit assignment (`/assignments/{task}/submit`).
- **Calendar:** View tasks deadline in a calendar format (`/calendar`).

**Teacher Portal (`/teacher/*`):**
- **Dashboard:** Overview of classes and recent submissions.
- **Classes:** 
  - List and create classes (`/classes`, `POST /classes`).
  - View class details (`/classes/{class}`).
  - Add students to a class (`POST /classes/{class}/students`).
- **Tasks/Assignments:** 
  - Add task to a class (`POST /classes/{class}/tasks`).
  - Update or delete tasks (`POST /classes/{class}/tasks/{task}`, `DELETE ...`).
- **Submissions:** 
  - View submissions for a task (`/tasks/{task}/submissions`).
  - View overall submission history (`/submissions`).
  - View specific submission details (`/submissions/{submission}`).
  - Mark submission as checked (`POST /submissions/{submission}/mark-as-checked`).
- **Calendar:** View deadlines and task schedules (`/calendar`).

---

## 2. Database Schema & Models

- **Users:** `id`, `name`, `email`, `password`, `role`, `is_approved`, timestamps.
- **SchoolClasses (Classes):** `id`, `teacher_id` (foreign key to users), `name`, `description`, timestamps.
- **Subjects:** `id`, `name`, timestamps.
- **Class_Student (Pivot):** `class_id`, `student_id`, timestamps.
- **Tasks:** `id`, `class_id`, `title`, `description`, `due_date`, `max_attempts` (or similar constraints), timestamps.
- **Task_Reference_Files:** `id`, `task_id`, `file_path`, timestamps.
- **Submissions:** `id`, `task_id`, `student_id`, `status` (e.g., pending, checked), `submitted_at`, timestamps. Support for multiple file uploads is implied by schema updates.

---

## 3. Project Rules & Coding Standards

The following rules are **strict** and must be followed in every implementation.

### 🗂️ File Architecture
All files must be placed in their designated directories. Never invent new top-level folders.

**Backend (`app/`)**
- **Controllers:** `app/Http/Controllers/` - Thin only. No business logic. Delegate immediately to a Service.
- **Services:** `app/Http/Services/` - All business logic lives here. One service per domain.
- **Repositories:** `app/Http/Repositories/` - All database queries (Eloquent/Query Builder). Services must not contain raw queries.
- **Form Requests:** `app/Http/Requests/` - Every controller action that accepts user input must use a dedicated Form Request class.
- **Middleware:** `app/Http/Middleware/` - Cross-cutting concerns only (auth, role, logging).
- **Models:** `app/Models/` - Eloquent models only. No query logic beyond scopes/relationships.

**Frontend (`resources/`)**
- **Blade Layouts:** `resources/views/layouts/` - Shell templates only (`app.blade.php`, `guest.blade.php`).
- **Blade Pages:** `resources/views/pages/` - Full page views extending layouts.
- **UI Components:** `resources/views/components/ui/` - Reusable atomic UI elements (buttons, cards, badges, modals, alerts).
- **Form Components:** `resources/views/components/forms/` - Reusable form controls.
- **Nav Components:** `resources/views/components/navigation/` - Navbar, sidebar, footer.
- **JS Hooks:** `resources/js/hooks/` - Barrel-exported state/side-effects.
- **JS Constants:** `resources/js/constants/` - Barrel-exported hardcoded semantic values.
- **Global CSS:** `resources/css/app.css` - Design tokens and global styles.
- **Component CSS:** `resources/css/components/` - One CSS file per component.

### 🧹 Code Cleanliness
- No dead code, `console.log`, or `dd()` in production code.
- **Single Responsibility:** Classes, functions, and components do exactly one thing.
- **Naming Conventions:** `PascalCase` for PHP classes, `camelCase` for PHP/JS methods and variables, `snake_case` for database columns, `kebab-case` for Blade files, `SCREAMING_SNAKE_CASE` for JS constants.
- Extract long methods (30+ lines) into helpers. No magic numbers or strings.

### 💬 Comment Policy
- Comments explaining logic (`// loops through users`) are strictly prohibited. Rewrite logic instead.
- **Allowed:** Blade section markers (`{{-- ========== HEADER ========== --}}`).
- **Not allowed:** Commented-out code, TODOs, inline notes.

### 🧩 UI Components
- Reusable elements must be Blade components in `components/ui/` or `components/forms/`.
- Must accept props via `@props([])` and contain no business logic.
- Styling comes exclusively from CSS design system or the component's own CSS file. Never use inline `style=""`.

### 🔒 Security & Input Sanitation
- **Backend:** All user input must be validated via Form Requests (`$request->validated()`). Database writes must use Eloquent/Query Builder. File uploads must be validated.
- **Frontend:** Never render raw HTML `{!! !!}` for user-supplied data; use `{{ }}`. Forms must include `@csrf`.

### 🎨 Styling & Design System
- All styles must use CSS classes. **No inline styles.**
- Use CSS custom properties (`var(--color-*)`) from `app.css`. Never hardcode colors in components.
- The project font is **Poppins**.
- **No Gradients.** Use solid colors exclusively.

### 🔁 Routing & General Principles
- All web routes are in `routes/web.php`. Route names use `kebab-case` and are namespaced by domain.
- **Consistency over convenience.** Follow established patterns.
- Fail loudly with proper HTTP status codes.
- No direct `DB::` facade calls outside Repositories.

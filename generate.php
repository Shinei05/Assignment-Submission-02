<?php

$dirs = [
    'app/Http/Controllers/Student',
    'app/Http/Controllers/Teacher',
    'app/Http/Services',
    'app/Http/Repositories',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$files = [
    'app/Http/Controllers/Student/DashboardController.php' => "<?php\n\nnamespace App\Http\Controllers\Student;\n\nuse App\Http\Controllers\Controller;\n\nclass DashboardController extends Controller\n{\n    public function index()\n    {\n        return view('pages.student.dashboard');\n    }\n}\n",
    'app/Http/Controllers/Student/ClassController.php' => "<?php\n\nnamespace App\Http\Controllers\Student;\n\nuse App\Http\Controllers\Controller;\n\nclass ClassController extends Controller\n{\n    public function index() { return view('pages.student.classes.index'); }\n    public function show(\$class) { return view('pages.student.classes.show'); }\n}\n",
    'app/Http/Controllers/Student/AssignmentController.php' => "<?php\n\nnamespace App\Http\Controllers\Student;\n\nuse App\Http\Controllers\Controller;\n\nclass AssignmentController extends Controller\n{\n    public function index() { return view('pages.student.assignments.index'); }\n    public function showTask(\$class, \$task) { return view('pages.student.assignments.show'); }\n    public function show(\$task) { return view('pages.student.assignments.show'); }\n    public function submit(\$task) { return back(); }\n}\n",
    'app/Http/Controllers/Student/CalendarController.php' => "<?php\n\nnamespace App\Http\Controllers\Student;\n\nuse App\Http\Controllers\Controller;\n\nclass CalendarController extends Controller\n{\n    public function index() { return view('pages.student.calendar'); }\n}\n",
    
    'app/Http/Controllers/Teacher/DashboardController.php' => "<?php\n\nnamespace App\Http\Controllers\Teacher;\n\nuse App\Http\Controllers\Controller;\n\nclass DashboardController extends Controller\n{\n    public function index() { return view('pages.teacher.dashboard'); }\n}\n",
    'app/Http/Controllers/Teacher/ClassController.php' => "<?php\n\nnamespace App\Http\Controllers\Teacher;\n\nuse App\Http\Controllers\Controller;\n\nclass ClassController extends Controller\n{\n    public function index() { return view('pages.teacher.classes.index'); }\n    public function store() { return back(); }\n    public function show(\$class) { return view('pages.teacher.classes.show'); }\n    public function addStudents(\$class) { return back(); }\n}\n",
    'app/Http/Controllers/Teacher/TaskController.php' => "<?php\n\nnamespace App\Http\Controllers\Teacher;\n\nuse App\Http\Controllers\Controller;\n\nclass TaskController extends Controller\n{\n    public function store(\$class) { return back(); }\n    public function update(\$class, \$task) { return back(); }\n    public function destroy(\$class, \$task) { return back(); }\n}\n",
    'app/Http/Controllers/Teacher/SubmissionController.php' => "<?php\n\nnamespace App\Http\Controllers\Teacher;\n\nuse App\Http\Controllers\Controller;\n\nclass SubmissionController extends Controller\n{\n    public function taskSubmissions(\$task) { return view('pages.teacher.submissions.index'); }\n    public function index() { return view('pages.teacher.submissions.index'); }\n    public function show(\$submission) { return view('pages.teacher.submissions.show'); }\n    public function markAsChecked(\$submission) { return back(); }\n}\n",
    'app/Http/Controllers/Teacher/CalendarController.php' => "<?php\n\nnamespace App\Http\Controllers\Teacher;\n\nuse App\Http\Controllers\Controller;\n\nclass CalendarController extends Controller\n{\n    public function index() { return view('pages.teacher.calendar'); }\n}\n",
];

foreach ($files as $path => $content) {
    if (!file_exists($path)) {
        file_put_contents($path, $content);
        echo "Created \$path\n";
    }
}

echo "Done generating controllers.";

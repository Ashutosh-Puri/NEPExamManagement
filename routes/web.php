<?php

use Livewire\Livewire;
use App\Livewire\HomeIndex;
use App\Livewire\User\Cap\AllCap;
use App\Livewire\User\Cgpa\AllCgpa;
use App\Livewire\User\Exam\AllExam;
use App\Livewire\User\Role\AllRole;
use App\Livewire\User\User\AllUser;
use App\Livewire\User\Home\UserHome;
use App\Livewire\User\UserDashboard;
use App\Livewire\User\Block\AllBlock;
use Illuminate\Support\Facades\Route;
use App\Livewire\User\Grade\AllGrades;
use App\Livewire\User\Course\AllCourse;
use App\Livewire\User\Credit\AllCredit;
use App\Livewire\User\Notice\AllNotice;
use App\Livewire\User\College\AllCollege;
use App\Livewire\User\ExamFee\AllExamFee;
use App\Livewire\User\Pattern\AllPattern;
use App\Livewire\User\Sanstha\AllSanstha;
use App\Livewire\Faculty\FacultyDashboard;
use App\Livewire\Faculty\Home\FacultyHome;
use App\Livewire\Student\Home\StudentHome;
use App\Livewire\Student\StudentDashboard;
use App\Livewire\User\Faculty\UserFaculty;
use App\Livewire\User\Observer\DBObserver;
use App\Livewire\User\Setting\SiteSetting;
use App\Livewire\Student\Helpline\Helpline;
use App\Livewire\User\Building\AllBuilding;
use App\Livewire\User\Exambody\AllExamBody;
use App\Livewire\User\ExamForm\AllExamForm;
use App\Livewire\User\Helpline\AllHelpline;
use App\Livewire\User\PaperSet\AllPaperSet;
use App\Livewire\User\Ratehead\AllRateHead;
use App\Livewire\User\RoleType\AllRoleType;
use App\Livewire\Faculty\Faculty\AllFaculty;
use App\Livewire\Faculty\Subject\AllSubject;
use App\Livewire\Student\StudentViewProfile;
use App\Livewire\User\StrongRoom\StrongRoom;
use App\Livewire\User\Classroom\AllClassroom;
use App\Livewire\User\ClassYear\AllClassYear;
use App\Livewire\User\ExamMarks\ExtMarkEntry;
use App\Livewire\User\ExamOrder\AllExamOrder;
use App\Livewire\User\ExamPanel\AllExamPanel;
use App\Livewire\User\Ordinace\OrdinaceApply;
use App\Livewire\User\Programme\AllProgramme;
use App\Livewire\User\Cap\RecordCapAttendance;
use App\Livewire\User\ExamForm\InwardExamForm;
use App\Livewire\User\Ordinace\AllOrdinace163;
use App\Livewire\User\Department\AllDepartment;
use App\Livewire\User\Unfairmean\AllUnfairmean;
use App\Livewire\User\University\AllUniversity;
use App\Http\Controllers\User\Cap\CapController;
use App\Livewire\Student\Payment\StudentPayment;
use App\Livewire\User\ExamSeatNo\GenerateSeatNo;
use App\Livewire\User\Examsupervision\Sendemail;
use App\Livewire\Faculty\ExamPanel\ViewExamPanel;
use App\Livewire\User\Blockmaster\AllBlockMaster;
use App\Livewire\User\CourseClass\AllCourseClass;
use App\Livewire\User\Examsession\AllExamsession;
use App\Livewire\User\FacultyRole\AllFacultyRole;
use App\Livewire\User\Instruction\AllInstruction;
use App\Livewire\User\Unfairmeans\AllUnfairMeans;
use App\Livewire\User\ExamMarks\ExtMarkPaperIssue;
use App\Livewire\User\AcademicYear\AllAcademicYear;
use App\Livewire\User\ExamBuilding\AllExamBuilding;
use App\Livewire\User\ExamMarks\ExtMarkEntryVerify;
use App\Livewire\User\PatternClass\AllPatternClass;
use App\Livewire\Faculty\AbsentEntry\AllAbsentEntry;
use App\Livewire\Faculty\FacultyHead\AllFacultyHead;
use App\Livewire\Faculty\SubjectType\AllSubjectType;
use App\Livewire\Faculty\UpdateProfile\UpdateProfile;
use App\Livewire\User\AdmissionData\AllAdmissionData;
use App\Livewire\User\ExamFeeCourse\AllExamFeeCourse;
use App\Livewire\User\ExamOrderPost\AllExamOrderPost;
use App\Livewire\User\HelplineQuery\AllHelplineQuery;
use App\Livewire\User\Instruction\AllInstructionType;
use App\Livewire\User\Ordinace\AllOrdinace163Student;
use App\Livewire\User\TimeTableSlot\AllTimeTableSlot;
use App\Livewire\User\ClassroomBlock\AllClassroomBlock;
use App\Livewire\User\DepartmentType\AllDepartmentType;
use App\Livewire\User\ExamTimeTable\ClassExamTimeTable;
use App\Http\Controllers\User\Barcode\BarcodeController;
use App\Livewire\Faculty\AssignSubject\AllAssignSubject;
use App\Livewire\Faculty\SubjectBucket\AllSubjectBucket;
use App\Livewire\User\FacultyOrder\GenerateFacultyOrder;
use App\Livewire\User\SubjectOrder\GenerateSubjectOrder;
use App\Livewire\Student\Ordinace\StudentOrdinace163Form;
use App\Livewire\Student\Profile\MultiStepStudentProfile;
use App\Livewire\User\BlockAllocation\AllBlockallocation;
use App\Livewire\User\BoardUniversity\AllBoardUniversity;
use App\Livewire\User\Examsupervision\AllExamsupervision;
use App\Livewire\User\ExamTimeTable\SubjectExamTimeTable;
use App\Livewire\User\PaperSubmission\AllPaperSubmission;
use App\Livewire\User\BlockAllocation\ExamBlockAllocation;
use App\Livewire\User\ExamForm\DeleteExamFormBeforeInward;
use App\Livewire\User\ExamTimeTable\VerticalExamTimeTable;
use App\Livewire\User\GenerateExamOrder\GenerateExamOrder;
use App\Http\Controllers\Student\Student\StudentController;
use App\Livewire\User\DepartmentPrefix\AllDepartmentPrefix;
use App\Livewire\User\ExamPatternClass\AllExamPatternClass;
use App\Livewire\User\HelplineDocument\AllHelplineDocument;
use App\Livewire\User\Ordinace\Ordinace163StudentMarkEntry;
use App\Livewire\Faculty\SubjectCategory\AllSubjectCategory;
use App\Livewire\Faculty\SubjectVertical\AllSubjectVertical;
use App\Livewire\User\ExamFormStatistics\ExamFormStatistics;
use App\Http\Controllers\Student\Razorpay\RazorPayController;
use App\Http\Controllers\User\StudentSeatno\SeatnoController;
use App\Livewire\Student\StudentExamForm\FillStudentExamForm;
use App\Livewire\User\EducationalCourse\AllEducationalCourse;
use App\Livewire\User\HodAppointSubject\AllHodAppointSubject;
use App\Http\Controllers\User\Unfairmean\UnfairmeanController;
use App\Livewire\Student\StudentExamForm\DeleteStudentExamForm;
use App\Livewire\User\ExamFormStatistics\ExamFormFeeHeadReport;
use App\Livewire\Faculty\HodExamFormReport\AllHodExamFormReport;
use App\Livewire\Faculty\InternalAudit\AssignTool\AllAssignTool;
use App\Livewire\Faculty\QuestionPaperBank\AllQuestionPaperBank;
use App\Livewire\User\ExamMarks\PendingExtMarkEntryVerifyReport;
use App\Livewire\User\QuestionPaperBank\QuestionPaperBankReport;
use App\Livewire\User\InternalAudit\InternalTool\AllInternalTool;
use App\Livewire\Faculty\InternalMarksBatch\AllInternalMarksBatch;
use App\Livewire\Faculty\InternalMarksEntry\AllInternalMarksEntry;
use App\Livewire\Faculty\SubjectwiseStudent\AllSubjectwiseStudent;
use App\Livewire\User\DocumentAcademicYear\AllDocumentAcademicYear;
use App\Http\Controllers\User\Examtimetable\Examtimetablecontroller;
use App\Livewire\Faculty\InternalAssessment\PendingHodIntAssessment;
use App\Livewire\Faculty\InternalAudit\HodAssignTool\AllHodAssignTool;
use App\Livewire\User\QuestionPaperBank\QuestionPaperBankConfirmation;
use App\Livewire\Faculty\HodExamFormStatistics\AllHodExamFormStatistics;
use App\Livewire\Faculty\InternalAudit\UploadDocument\AllUploadDocument;
use App\Http\Controllers\User\Examordercontroller\ExamOrderPdfController;
use App\Livewire\Faculty\QuestionPaperBank\FacultyQuestionPaperBankReport;
use App\Livewire\Faculty\QuestionPaperBankDownload\QuestionPaperBankDownload;
use App\Http\Controllers\User\ExamFormStatistics\ExamFormReportViewController;
use App\Livewire\Faculty\InternalAudit\AuditInternalTool\AllAuditInternalTool;
use App\Livewire\User\InternalAudit\InternalToolAuditor\AllInternalToolAuditor;
use App\Livewire\Faculty\AppointInternalMarksBatch\AllAppointInternalMarksBatch;
use App\Http\Controllers\Faculty\SubjectwiseStudent\SubjectwiseStudentController;
use App\Livewire\User\InternalAudit\InternalToolDocument\AllInternalToolDocument;
use App\Livewire\Faculty\InternalAudit\HeadWiseInternalTool\AllHeadWiseInternalTool;
use App\Livewire\Faculty\InternalAudit\HodSubjectToolReport\AllHodSubjectToolReport;
use App\Http\Controllers\Faculty\QuestionPaperBankPdf\QuestionPaperBankPdfController;
use App\Http\Controllers\Faculty\InternalAudit\HodInternalToolReport\HodInternalToolReportController;
use App\Http\Controllers\Faculty\InternalAudit\HodInternalToolDocumentReport\HodInternalToolDocumentReportController;
use App\Http\Controllers\Faculty\InternalMarks\InternalMarksController;



// Livewire Update Route
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});

// Livewire JS Route
Livewire::setScriptRoute(function ($handle) {
    return Route::get('/livewire/livewire.js', $handle);
});

// Guest Routes
Route::middleware(['guest'])->group(function ()
{

  // Guest Home
  Route::get('/',HomeIndex::class)->name('home');

  // Student Home
  Route::get('/student',StudentHome::class)->name('student');

  // User Home
  Route::get('/user',UserHome::class)->name('user');

  // Faculty Home
  Route::get('/faculty',FacultyHome::class)->name('faculty');

});



// Auth Student Routes
Route::prefix('student')->name('student.')->middleware(['auth:student','is_student','verified:student.verification.notice'])->group(function ()
{

    // Student Dashboard
    Route::get('/dashboard',StudentDashboard::class)->name('dashboard');

    // Student Profile
    Route::get('/profile',MultiStepStudentProfile::class)->name('profile');

    // Student View Profile
    Route::get('/view/profile',StudentViewProfile::class)->name('view-profile');

    // Student Helpline
    Route::get('/helpline',Helpline::class)->name('helpline');

    // Student Ordinace 163 Form
    Route::get('/student/payments',StudentPayment::class)->name('payments');

    // Student Exam Form
    Route::post('/exam/form',FillStudentExamForm::class)->name('student_exam_form');

    // Student Delete Exam Form
    Route::post('/delete/exam/form',DeleteStudentExamForm::class)->name('student_delete_exam_form');

    // Student Ordinace 163 Form
    Route::post('/student/ordinace/163/form',StudentOrdinace163Form::class)->name('student_ordinace_163_form');

    // Student Print Preview Exam Form
    Route::post('/print/preview/exam/form', [StudentController::class,'print_preview_exam_form'])->name('student_print_preview_exam_form');

    // Student Print Final Exam Form
    Route::post('/print/final/exam/form', [StudentController::class,'print_final_exam_form'])->name('student_print_final_exam_form');

    // Student Print Fee Reciept
    Route::post('/print/exam/form/fee/recipet', [StudentController::class,'print_exam_form_fee_recipet'])->name('student_print_exam_form_fee_recipet');

    // Student  Hallticket Print
    Route::post('/download/hallticket', [StudentController::class,'download_hallticket'])->name('student_download_hallticket');

    // Student Pay Exam Form Fee
    Route::post('/pay/exam/form/fee', [RazorPayController::class,'student_pay_exam_form_fee'])->name('student_pay_exam_form_fee');

    // Student Verify Exam Form Fee Payment
    Route::post('/verify/exam/form/fee/payment', [RazorPayController::class,'student_verify_exam_form_payment'])->name('student_verify_exam_form_payment');

    // Student Fail Exam Form Fee Payment
    Route::post('/failed/exam/form/fee/payment', [RazorPayController::class,'student_failed_exam_form_payment'])->name('student_failed_exam_form_payment');

    // Student Exam Form Fee Refund
    Route::post('/refund/exam/form/fee', [RazorPayController::class,'student_refund_exam_form'])->name('student_refund_exam_form');

    // Student Pay ordinace 163 Form Fee
    Route::post('/pay/ordinace/163/form/fee', [RazorPayController::class,'student_pay_ordinace_163_form_fee'])->name('student_pay_ordinace_163_form_fee');

    // Student Verify ordinace 163 Form Fee Payment
    Route::post('/verify/ordinace/163/form/fee/payment', [RazorPayController::class,'student_verify_ordinace_163_form_payment'])->name('student_verify_ordinace_163_form_payment');

    // Student Fail ordinace 163 Form Fee Payment
    Route::post('/failed/ordinace/163/form/fee/payment', [RazorPayController::class,'student_failed_ordinace_163_form_payment'])->name('student_failed_ordinace_163_form_payment');

    // Student Print ordinace 163 Fee Reciept
    Route::post('/print/ordinace/163/form/fee/recipet', [StudentController::class,'student_print_ordinace_163_form_fee_recipet'])->name('student_print_ordinace_163_form_fee_recipet');

});


// Auth Faculty Routes
Route::prefix('faculty')->name('faculty.')->middleware(['auth:faculty','verified:faculty.verification.notice'])->group(function ()
{

    // Faculty Dashboard
    Route::get('dashboard', FacultyDashboard::class)->name('dashboard');

    // Role CEO
    Route::middleware(['canany:CEO'])->group(function () {

      // Question Paper Bank Downlaod
      Route::get('/question/paper/bank/downlaod',QuestionPaperBankDownload::class)->name('question_paper_bank_download');

      // Question Paper Bank PDF Download
      Route::post('/question/paper/bank/download',[QuestionPaperBankPdfController::class,'download_question_paper'])->name('download_question_paper');

    });

    // Role Head
    Route::middleware(['canany:Head'])->group(function () {

      // All Faculty
      Route::get('/faculties', AllFaculty::class)->name('all_faculties');

      // All FacultyHead
      Route::get('/facultyheads', AllFacultyHead::class)->name('all_faculty_heads');

      // All Hod Assign Tool
      Route::get('/hod/assign/tools', AllHodAssignTool::class)->name('all_hod_assign_tools');

      // All ViewExamPanel
      Route::get('/view/exampanel', ViewExamPanel::class)->name('view_exam_panels');

      // Print Hod Internal Tool Report
      Route::post('/hod/internal/tool/document/report', [HodInternalToolDocumentReportController::class,'download_subject_internal_tool_document_report'])->name('download_subject_internal_tool_document_report');

      // All Hod subject Internal Tool Report
      Route::get('/hod/subject/internal/tool/document/report', PendingHodIntAssessment::class)->name('download_hod_subject_tool_assessment_report');

      // All Subject Categories
      Route::get('/subject/categories', AllSubjectCategory::class)->name('all_subject_categories');

      // All Subject Verticals
      Route::get('/subject/verticals', AllSubjectVertical::class)->name('all_subject_verticals');

      // All Subject
      Route::get('/subject', AllSubject::class)->name('all_subjects');

      // All Allocate Subject
      Route::get('/assign/subject', AllAssignSubject::class)->name('all_assign_subjects');

      // All SubjectBucket
      Route::get('/subject/bucket', AllSubjectBucket::class)->name('all_subject_buckets');

      // All SubjectType
      Route::get('/subject/types', AllSubjectType::class)->name('all_subject_types');

      // Headwise Internal Tool Report
      Route::get('/headwise/internal/tool/',AllHeadWiseInternalTool::class)->name('all_headwise_internal_tools');

      // Headwise Internal Tool Report View
      Route::post('/headwise/internal/tool/view/',[HodInternalToolReportController::class,'headwise_internal_tool_report_view'])->name('headwise_internal_tool_report_view');

      // All Hod Subject Tool Report
      Route::get('/hod/subject/tool/report', AllHodSubjectToolReport::class)->name('all_hod_subject_tool_report');

      // All Hod Exam Form Report
      Route::get('/hod/exam/form/report', AllHodExamFormReport::class)->name('all_hod_exam_form_report');

      // All Hod Exam Form Statistics
      Route::get('/hod/exam/form/statistics', AllHodExamFormStatistics::class)->name('all_hod_exam_form_statistics');

      // All Hod Subjectwise Students
      Route::get('/hod/subjectwise/student/', AllSubjectwiseStudent::class)->name('all_hod_subjectwise_student');

      // Print Hod Internal Tool Report
      Route::post('/hod/subjectwise/student/pdf/report', [SubjectwiseStudentController::class,'download_subjectwise_student_report'])->name('download_subjectwise_student_report');

       // Print Hod Internal Tool Report
      Route::post('/hod/subjectwise/student/excel/report', [SubjectwiseStudentController::class,'download_subjectwise_student_excel_report'])->name('download_subjectwise_student_excel_report');

      // All Hod Internal Mark Batch
      Route::get('/hod/internal/mark/batch', AllInternalMarksBatch::class)->name('all_internal_mark_batch');

      // All Hod Appoint Internal Mark Batch
      Route::get('/hod/appoint/internal/mark/batch', AllAppointInternalMarksBatch::class)->name('all_appoint_internal_mark_batch');

      // All Internal Marks Entry
      Route::get('/internal/mark/entry', AllInternalMarksEntry::class)->name('all_internal_mark_entry');

      // All Absent Entry
      Route::get('/absent/entry', AllAbsentEntry::class)->name('all_absent_entry');

      // Preview Marks
      Route::post('/internal/marks/preview', [InternalMarksController::class,'preview_marks'])->name('preview_marks');

      // Print Marks
      Route::post('/internal/marks/print', [InternalMarksController::class,'print_marks'])->name('print_marks');

    });


    // Role Teacher
    Route::middleware(['canany:Teacher'])->group(function () {

      // Update Faculty Profile
      Route::get('/update/profile', UpdateProfile::class)->name('update_profile');

      // All Question Paper Banks
      Route::get('/question/paper/bank', AllQuestionPaperBank::class)->name('all_question_paper_bank');

      //Question Paper Bank Report
      Route::get('/question/paper/bank/report',FacultyQuestionPaperBankReport::class)->name('faculty_question_paper_bank_report');

      // Question Paper Bank PDF Preview
      Route::post('/question/paper/bank/preview',[QuestionPaperBankPdfController::class,'preview_question_paper'])->name('preview_question_paper');

      // All Assign Tool
      Route::get('/assign/tools/{mode?}', AllAssignTool::class)->name('all_assign_tools');

      // All Document Upload
      Route::get('/upload/documents', AllUploadDocument::class)->name('all_upload_documents');

      // Audit Internal Tool
      Route::get('/audit/internal/tool',AllAuditInternalTool::class)->name('all_audit_internal_tools');

    });

});



// Auth User Routes
Route::prefix('user')->name('user.')->middleware(['auth:user','is_user','verified:user.verification.notice'])->group(function () {

  // User Dashboard
  Route::get('dashboard', UserDashboard::class)->name('dashboard');

  // Role Super Admin
  Route::middleware(['can:User Super Admin'])->group(function () {

    // All Role
    Route::get('/roles', AllRole::class)->name('all_roles');

    // All Role Types
    Route::get('/role/types', AllRoleType::class)->name('all_role_types');

    // All Faculty Role
    Route::get('/faculty/roles', AllFacultyRole::class)->name('all_faculty_roles');

    // All Setting
    Route::get('site/setting', SiteSetting::class)->name('site_setting');

    //All College
    Route::get('/colleges', AllCollege::class)->name('all_colleges');

    //All Sanstha
    Route::get('/sansthas', AllSanstha::class)->name('all_sanstha');

    //All University
    Route::get('/universities', AllUniversity::class)->name('all_university');

    //All Academic Year
    Route::get('/board/universities',AllBoardUniversity::class)->name('all_board_university');

    //All CGPA
    Route::get('/cgpas', AllCgpa::class)->name('all_cgpa');

    //All Grade
    Route::get('/grades', AllGrades::class)->name('all_grade');

    //All Programmes
    Route::get('/programmes',AllProgramme::class)->name('all_programme');

    //All Courses
    Route::get('/courses',AllCourse::class)->name('all_course');

    //All Class Years
    Route::get('/class/years',AllClassYear::class)->name('all_class_year');

    //All Course Class
    Route::get('/course/classes',AllCourseClass::class)->name('all_course_class');

    //All Pattern
    Route::get('/patterns', AllPattern::class)->name('all_pattern');

    //All Pattern Class
    Route::get('/pattern/classes',AllPatternClass::class)->name('all_pattern_class');

    //All Academic Year
    Route::get('/academic/years',AllAcademicYear::class)->name('all_academic_year');

    //All Document Academic Year
    Route::get('/document/academic/years',AllDocumentAcademicYear::class)->name('all_document_academic_year');

    //All Educational Course
    Route::get('/educational/courses', AllEducationalCourse::class)->name('all_educational_course');

  });

  // Role Admin
  Route::middleware(['can:User Admin'])->group(function () {

    //All Exam
    Route::get('/exams', AllExam::class)->name('all_exam');

    //All Exam Pattern Class
    Route::get('/exam/pattern/classes',AllExamPatternClass::class)->name('all_exam_pattern_class');

    //All  Instruction Type
    Route::get('/instruction/type',AllInstructionType::class)->name('all_instruction_type');

    //All Instruction
    Route::get('/instruction',AllInstruction::class)->name('all_instruction');

    //All Exam Body
    Route::get('/exam/body',AllExamBody::class)->name('all_exam_body');

    //Exam Form Statistics
    Route::get('/exam/form/statistics',ExamFormStatistics::class)->name('exam_form_statistics');

    //Exam Form Statistics Report View
    Route::get('/exam/form/report/view/{exam_pattern_class_id}{status}',[ExamFormReportViewController::class,'exam_form_report_view'])->name('exam_form_report_view');

    //Exam Form Fee Head Statistics
    Route::get('/exam/form/fee/head/statistics',ExamFormFeeHeadReport::class)->name('exam_form_fee_head_statistics');

    //All Departments
    Route::get('/departments', AllDepartment::class)->name('all_department');

    //All Department Types
    Route::get('/department/types', AllDepartmentType::class)->name('all_department_types');

    //All Subject Hod
    Route::get('/hod/appoint/subject',  AllHodAppointSubject::class)->name('all_hod_appoint_subjects');

    //All Credits
    Route::get('/credits', AllCredit::class)->name('all_credit');

    //All Buildings
    Route::get('/building', AllBuilding::class)->name('all_builidng');

    //All Blocks
    Route::get('/blocks', AllBlock::class)->name('all_block');

    //All Blockmaster
    Route::get('/block/master', AllBlockMaster::class)->name('all_block_master');

    //All Classroom
    Route::get('/classroom', AllClassroom::class)->name('all_classroom');

    //All Classroom Block
    Route::get('/classroom/block', AllClassroomBlock::class)->name('all_classroom_block');

    //All Block Allocation
    Route::get('/block/allocation', AllBlockallocation::class)->name('all_block_allocation');

    //Block Allocation
    Route::get('all/block/allocation', ExamBlockAllocation::class)->name('block_allocation');

    //All Exam Building
    Route::get('/exam/building', AllExamBuilding::class)->name('all_exam_building');

    //All Exam Session
    Route::get('/exam/session', AllExamsession::class)->name('all_exam_session');

    //All Exam Supervision
    Route::get('/exam/supervision', AllExamsupervision::class)->name('all_exam_supervision');

    //send supervision mail
    Route::get('/exam/supervision/mail', Sendemail::class)->name('all_exam_supervision_mail');

    //All Exam Fee
    Route::get('/exam/fees',AllExamFee::class)->name('all_exam_fee');

    //All Exam Fee Course
    Route::get('/exam/fee/course',AllExamFeeCourse::class)->name('all_exam_fee_course');

    //AllvExam Order Post
    Route::get('/exam/order/post', AllExamOrderPost::class)->name('all_exam_order_post');

    //Generate faculty order
    Route::get('/generate/faculty/order', GenerateFacultyOrder::class)->name('generate_faculty_order');

    //All Exam Panel
    Route::get('/exampanel', AllExamPanel::class)->name('all_exam_panel');

    //Generate Exam Order
    Route::get('/generate/exam/order', GenerateExamOrder::class)->name('generate_exam_order');

    //All Exam Order
    Route::get('/exam/order', AllExamOrder::class)->name('all_exam_order');

    //All Exam Order Generate Exam Order PDF
    Route::get('exam/order/{id}/{token}', [ExamOrderPdfController::class, 'order'])->name('examorder');

    //All Exam Order Generate merge order
    Route::get('merge/order/{id}/{token}', [ExamOrderPdfController::class, 'merge'])->name('mergeorder');

    //All Exam Order Cancel Exam Order
    Route::get('cancel/exam/order/{id}',[ExamOrderPdfController::class,'cancelorder'])->name('cancelorder');

    //Generate Subject Order
    Route::get('/generate/subject/order', GenerateSubjectOrder::class)->name('generate_subject_order');

    //All Notice
    Route::get('/notices',AllNotice::class)->name('all_notice');

    //All Ratehead
    Route::get('/ratehead', AllRateHead::class)->name('all_ratehead');

    //All Users
    Route::get('/users', AllUser::class)->name('all_user');

    //All Admission Data
    Route::get('/admission/datas',AllAdmissionData::class)->name('all_admission_data');

    //Generate Seat no
    Route::get('/exam/student/seatno',GenerateSeatNo::class)->name('all_seat_no');

    //seatno pdf
    Route::get('exam/student/seatno/pdf/{exampatternclass}',[SeatnoController::class,'seatnopdf'])->name('seat_nos');

    // marklist view
    Route::get('/marklist',[BarcodeController::class,'marklist'])->name('marklist');

    //generate marklist
    Route::post('/generate/marklist',[BarcodeController::class,'generate_marklist'])->name('generate_marklist');

    //generate barcode
    Route::get('/generate/barcode/sticker/{examdate}/ts/{timeslot_id}',[BarcodeController::class,'generate_barcode'])->name('generate_barcode');

    //download barcode
    Route::get('/download/barcode/sticker/{examdate}/ts/{timeslot_id}', [BarcodeController::class, 'download_barcode'])->name('download_barcode');

    //cap attendence
    Route::get('cap/attendance',RecordCapAttendance::class)->name('cap_attendance');

    // seal bag view
    Route::get('/seal/bag/report', [ BarcodeController::class,'seal_bag_report'])->name('seal_bag_report');

    // seal bag create
    Route::post('/seal/bag/report/create', [ BarcodeController::class,'seal_bag_report_create'])->name('seal_bag_report_create');

    // Paper Issue
    Route::get('/external/marks/paper/issue', ExtMarkPaperIssue::class)->name('external_marks_paper_issue');

    //scan barcode and insert marks for external
    Route::get('/external/marks/marks/entry', ExtMarkEntry::class)->name('external_marks_entry');

    //verification external marks entry
    Route::get('/external/marks/verification/marks/entry', ExtMarkEntryVerify::class)->name('external_marks_entry_verify');

    //pending external marks entry
    Route::get('/pending/external/marks/verification/marks/entry',PendingExtMarkEntryVerifyReport::class)->name('pending_external_marks_entry_verify');

    //apply ordinace
    Route::get('/apply/ordinace', OrdinaceApply::class)->name('apply_ordinace');

    // ordinace 163
    Route::get('all/ordinace/163', AllOrdinace163::class)->name('all_ordinace_163');

    // all  ordinace 163 students
    Route::get('all/ordinace/163/students', AllOrdinace163Student::class)->name('all_ordinace_163_student');

    // all ordinace 163 student mark entry
    Route::get('ordinace/163/students/mark/entry', Ordinace163StudentMarkEntry::class)->name('ordinace_163_student_mark_entry');


    Route::get('/rnd', [CapController::class, 'index'])->name('in');

    // db logs
    Route::get('/db_log', DBObserver::class);

  });

  // Role Admin Clerk
  Route::middleware(['can:User Admin Clerk'])->group(function () {

    //All Time Table Slot
    Route::get('/time/table/slots',AllTimeTableSlot::class)->name('all_time_table_slot');

    //Delete Exam Form Before Inward
    Route::get('/delete/exam/form/before/inward',DeleteExamFormBeforeInward::class)->name('delete_exam_form_before_inward');

    //All Exam Time Table Class-Wise
    Route::get('/class/wise/exam/timetable', ClassExamTimeTable::class)->name('class_wise_exam_time_table');

    //Exam time table verticals wise
    Route::get('/vertical/wise/exam/timetable', VerticalExamTimeTable::class)->name('vertical_wise_exam_time_table');

    //All Exam Time Table Subject-Wise
    Route::get('/subject/wise/exam/timetable', SubjectExamTimeTable::class)->name('subject_wise_exam_time_table');

    //Exam timetable Pdf
    Route::get('exam/time/table/pdf/{exampatternclass}',[Examtimetablecontroller::class,'exam_time_table_pdf'])->name('exam_time_table_pdf');

    // //Exam time table xls
    Route::get('exam/time/table/excel/{exampatternclass}',[Examtimetablecontroller::class,'exam_time_table_excel'])->name('exam_time_table_excel');


    //All Cap
    Route::get('/caps',AllCap::class)->name('all_cap');

    //All Exam Form
    Route::get('/exam/form',AllExamForm::class)->name('all_exam_form');

    //Inward Exam Form
    Route::get('/inward/exam/form',InwardExamForm::class)->name('inward_exam_form');

    //Modify Exam Form  CRUD Canceled
    // Route::get('/modify/exam/form',ModifyExamForm ::class)->name('modify_exam_form');

    // All Department Prefix
    Route::get('/department/prefixes', AllDepartmentPrefix::class)->name('all_department_prefixes');

    // Internal Tool Auditor
    Route::get('/internal/tool/auditor',AllInternalToolAuditor::class)->name('all_internal_tool_auditors');

    // All Internal Tool Master
    Route::get('/internal/tool', AllInternalTool::class)->name('all_internal_tool');

    // All Internal Tool Document
    Route::get('/internal/tool/documents', AllInternalToolDocument::class)->name('all_internal_tool_documents');

    //All Unfairmean master
    Route::get('/unfairmean', AllUnfairMeans::class)->name('all_unfairmean');

    //All Unfairmean
    Route::get('/unfairmeans', AllUnfairmean::class)->name('all_unfairmeans');

    //All Unfairmean Attendence Report
    Route::get('/unfairmean/attendance/pdf',[UnfairmeanController::class,'unfairmeanattendance'])->name('unfairmean_attendance');

    //All Unfairmean Final Report
    Route::get('/unfairmean/report/pdf',[UnfairmeanController::class,'unfairmeanreport'])->name('unfairmean_finalreport');

    //All Unfairmean Performance Cancel Report
    Route::get('/performancecancel/report/pdf',[UnfairmeanController::class,'performancecancelreport'])->name('performance_cancelreport');


    //All Student Helpline
    Route::get('/helplines',AllHelpline::class)->name('all_helpline');

    //All Student Helpline Query
    Route::get('/helpline/queries',AllHelplineQuery::class)->name('all_helpline_query');

    //All Student Helpline Documnet
    Route::get('/helpline/documents',AllHelplineDocument::class)->name('all_helpline_document');

    //User Faculty
    Route::get('/faculty', UserFaculty::class)->name('all_faculty');

    //All Cap
    Route::get('/caps',AllCap::class)->name('all_cap');

  });

  // Role CEO
  Route::middleware(['can:User CEO'])->group(function () {

    //All PaperSet
    Route::get('/paper/set', AllPaperSet::class)->name('all_paperset');

    //All PaperSubmission
    Route::get('/paper/submission', AllPaperSubmission::class)->name('all_paper_submission');

    //Question Paper Bank Confirm
    Route::get('/question/paper/bank/confirm',QuestionPaperBankConfirmation::class)->name('question_paper_bank_confirm');

    //Question Paper Bank Report
    Route::get('/question/paper/bank/report',QuestionPaperBankReport::class)->name('question_paper_bank_report');

    //Strong Room
    Route::get('/strong/room',StrongRoom::class)->name('strong_room');

  });

});





require __DIR__.'/student.php';
require __DIR__.'/faculty.php';
require __DIR__.'/user.php';

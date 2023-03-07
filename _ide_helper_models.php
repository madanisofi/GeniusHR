<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AccountList
 *
 * @property int $id
 * @property string $account_name
 * @property float $initial_balance
 * @property string $account_number
 * @property string $branch_code
 * @property string $bank_branch
 * @property string|null $auto_payroll
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereAutoPayroll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereBankBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereBranchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereInitialBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountList whereUpdatedAt($value)
 */
	class AccountList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AdditionalInformation
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $can_insert
 * @property int $send_notification
 * @property int $reminder
 * @property int $is_required
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereCanInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereSendNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalInformation whereUpdatedAt($value)
 */
	class AdditionalInformation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Allowance
 *
 * @property int $id
 * @property int $employee_id
 * @property int $allowance_option
 * @property string $title
 * @property int $amount
 * @property string|null $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereAllowanceOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allowance whereUpdatedAt($value)
 */
	class Allowance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AllowanceOption
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceOption whereUpdatedAt($value)
 */
	class AllowanceOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property int $branch_id
 * @property string $department_id
 * @property string $employee_id
 * @property string $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AnnouncementEmployee
 *
 * @property int $id
 * @property int $announcement_id
 * @property int $employee_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereAnnouncementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementEmployee whereUpdatedAt($value)
 */
	class AnnouncementEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Appraisal
 *
 * @property int $id
 * @property int $branch
 * @property int $employee
 * @property string|null $rating
 * @property string $appraisal_date
 * @property int $customer_experience
 * @property int $marketing
 * @property int $administration
 * @property int $professionalism
 * @property int $integrity
 * @property int $attendance
 * @property string|null $remark
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @property-read \App\Models\Employee|null $employees
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereAdministration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereAppraisalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereCustomerExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereIntegrity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereProfessionalism($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appraisal whereUpdatedAt($value)
 */
	class Appraisal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Asset
 *
 * @property int $id
 * @property string $name
 * @property string $purchase_date
 * @property string $supported_date
 * @property float $amount
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereSupportedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUpdatedAt($value)
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AttendanceEmployee
 *
 * @property int $id
 * @property int $employee_id
 * @property string $date
 * @property string|null $end_date
 * @property string $status
 * @property int|null $parent_id
 * @property string|null $approve
 * @property string|null $notes
 * @property string $clock_in
 * @property string $clock_out
 * @property string $late
 * @property string $early_leaving
 * @property string $overtime
 * @property string $total_rest
 * @property string|null $working_hours
 * @property string|null $working_late
 * @property float $salary_cuts
 * @property int|null $shift_id
 * @property string|null $images
 * @property string|null $images_out
 * @property string|null $images_reason
 * @property string|null $reason
 * @property int|null $permissiontype_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employee
 * @property-read \App\Models\Employee|null $employees
 * @property-read \App\Models\LateCharge|null $latecharge
 * @property-read \App\Models\PermissionType|null $permission
 * @property-read \App\Models\Shift|null $shift
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereEarlyLeaving($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereImagesOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereImagesReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereLate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereOvertime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee wherePermissiontypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereSalaryCuts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereTotalRest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereWorkingHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceEmployee whereWorkingLate($value)
 */
	class AttendanceEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Award
 *
 * @property int $id
 * @property int $employee_id
 * @property string $award_type
 * @property string $date
 * @property string $gift
 * @property string $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Award newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Award query()
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereAwardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Award whereUpdatedAt($value)
 */
	class Award extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AwardType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardType whereUpdatedAt($value)
 */
	class AwardType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Branch
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChFavorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorite_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereFavoriteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereUserId($value)
 */
	class ChFavorite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChMessage
 *
 * @property int $id
 * @property string $type
 * @property int $from_id
 * @property int $to_id
 * @property string|null $body
 * @property string|null $attachment
 * @property int $seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereUpdatedAt($value)
 */
	class ChMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Commission
 *
 * @property int $id
 * @property int $employee_id
 * @property string $title
 * @property int $amount
 * @property string|null $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereUpdatedAt($value)
 */
	class Commission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CompanyPolicy
 *
 * @property int $id
 * @property int $branch
 * @property string $title
 * @property string $description
 * @property string|null $attachment
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPolicy whereUpdatedAt($value)
 */
	class CompanyPolicy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Competencies
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies query()
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Competencies whereUpdatedAt($value)
 */
	class Competencies extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $complaint_from
 * @property int $complaint_against
 * @property string $title
 * @property string $complaint_date
 * @property string $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereComplaintAgainst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereComplaintDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereComplaintFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 */
	class Complaint extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contract
 *
 * @property-read \App\Models\ContractAttechment|null $ContractAttechment
 * @property-read \App\Models\ContractComment|null $ContractComment
 * @property-read \App\Models\ContractNote|null $ContractNote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ContractComment[] $comment
 * @property-read int|null $comment_count
 * @property-read \App\Models\ContractType|null $contract_type
 * @property-read \App\Models\User|null $employee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ContractAttechment[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ContractNote[] $note
 * @property-read int|null $note_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 */
	class Contract extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContractAttechment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ContractAttechment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractAttechment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractAttechment query()
 */
	class ContractAttechment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContractComment
 *
 * @property-read \App\Models\User|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder|ContractComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractComment query()
 */
	class ContractComment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContractNote
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ContractNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractNote query()
 */
	class ContractNote extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContractType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContractType whereUpdatedAt($value)
 */
	class ContractType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Counting
 *
 * @property int $id
 * @property string $group_id
 * @property string $type
 * @property int|null $start_year
 * @property int $max_year
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Counting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereMaxYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counting whereUpdatedAt($value)
 */
	class Counting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $discount
 * @property int $limit
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomQuestion
 *
 * @property int $id
 * @property string $question
 * @property string|null $is_required
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomQuestion whereUpdatedAt($value)
 */
	class CustomQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeductionOption
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeductionOption whereUpdatedAt($value)
 */
	class DeductionOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Department
 *
 * @property int $id
 * @property int $branch_id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Deposit
 *
 * @property int $id
 * @property int $account_id
 * @property int $amount
 * @property string $date
 * @property int $income_category_id
 * @property int $payer_id
 * @property int $payment_type_id
 * @property string|null $referal_id
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountList|null $accounts
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereIncomeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit wherePayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereReferalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deposit whereUpdatedAt($value)
 */
	class Deposit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Designation
 *
 * @property int $id
 * @property int $department_id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereUpdatedAt($value)
 */
	class Designation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $name
 * @property string $is_required
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DucumentUpload
 *
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string $document
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DucumentUpload whereUpdatedAt($value)
 */
	class DucumentUpload extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Educations
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Educations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Educations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Educations query()
 * @method static \Illuminate\Database\Eloquent\Builder|Educations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educations whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educations whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Educations whereUpdatedAt($value)
 */
	class Educations extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailTemplate
 *
 * @property int $id
 * @property string $name
 * @property string|null $from
 * @property string|null $slug
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdatedAt($value)
 */
	class EmailTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailTemplateLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $lang
 * @property string $subject
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateLang whereUpdatedAt($value)
 */
	class EmailTemplateLang extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Employee
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $dob
 * @property string|null $birthplace
 * @property string $gender
 * @property Employee|null $phone
 * @property string $address
 * @property string $email
 * @property string $password
 * @property string $employee_id
 * @property string|null $employee_no
 * @property int|null $branch_id
 * @property int|null $department_id
 * @property int|null $designation_id
 * @property int|null $position_id
 * @property int|null $employeetype_id
 * @property int|null $room_id
 * @property int|null $group_now
 * @property int|null $role_id
 * @property int|null $education_id
 * @property string|null $company_doj
 * @property string|null $documents
 * @property string|null $additionals
 * @property string|null $account_holder_name
 * @property string|null $account_number
 * @property string|null $bank_name
 * @property string|null $bank_identifier_code
 * @property string|null $branch_location
 * @property string|null $tax_payer_id
 * @property int|null $salary_type
 * @property float|null $salary
 * @property float|null $consumption_fee
 * @property int $is_active
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\Designation|null $designation
 * @property-read \App\Models\Educations|null $education
 * @property-read \App\Models\EmployeeType|null $employeetype
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\PaySlip|null $paySlip
 * @property-read \App\Models\Position|null $position
 * @property-read \Spatie\Permission\Models\Role|null $role
 * @property-read \App\Models\PayslipType|null $salaryType
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAccountHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAdditionals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBankIdentifierCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBirthplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBranchLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCompanyDoj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereConsumptionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEducationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeetypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereGroupNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereSalaryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereTaxPayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUserId($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmployeeAdditionalInformation
 *
 * @property int $id
 * @property string $employee_id
 * @property int $additional_id
 * @property string $additional_value
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AdditionalInformation|null $information
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereAdditionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereAdditionalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeAdditionalInformation whereUpdatedAt($value)
 */
	class EmployeeAdditionalInformation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmployeeDocument
 *
 * @property int $id
 * @property string $employee_id
 * @property int $document_id
 * @property string $document_value
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereDocumentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocument whereUpdatedAt($value)
 */
	class EmployeeDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmployeeType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeType whereUpdatedAt($value)
 */
	class EmployeeType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Event
 *
 * @property int $id
 * @property int $branch_id
 * @property string $department_id
 * @property string $employee_id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string $color
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EventEmployee
 *
 * @property int $id
 * @property int $event_id
 * @property int $employee_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventEmployee whereUpdatedAt($value)
 */
	class EventEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Expense
 *
 * @property int $id
 * @property int $account_id
 * @property int $amount
 * @property string $date
 * @property int $expense_category_id
 * @property int $payee_id
 * @property int|null $payment_type_id
 * @property string|null $referal_id
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountList|null $accounts
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense wherePayeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereReferalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExpenseType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseType whereUpdatedAt($value)
 */
	class ExpenseType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GoalTracking
 *
 * @property int $id
 * @property int $branch
 * @property int $goal_type
 * @property string $start_date
 * @property string $end_date
 * @property string|null $subject
 * @property string|null $rating
 * @property string|null $target_achievement
 * @property string|null $description
 * @property int $status
 * @property int $progress
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @property-read \App\Models\GoalType|null $goalType
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereGoalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereTargetAchievement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalTracking whereUpdatedAt($value)
 */
	class GoalTracking extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GoalType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoalType whereUpdatedAt($value)
 */
	class GoalType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property string|null $class_first
 * @property string|null $class_end
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereClassEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereClassFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Holiday
 *
 * @property int $id
 * @property string $date
 * @property string $occasion
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday query()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereOccasion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereUpdatedAt($value)
 */
	class Holiday extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IncomeType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeType whereUpdatedAt($value)
 */
	class IncomeType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Indicator
 *
 * @property int $id
 * @property int $branch
 * @property int $department
 * @property int $designation
 * @property string|null $rating
 * @property int $customer_experience
 * @property int $marketing
 * @property int $administration
 * @property int $professionalism
 * @property int $integrity
 * @property int $attendance
 * @property int $created_user
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @property-read \App\Models\Department|null $departments
 * @property-read \App\Models\Designation|null $designations
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereAdministration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCreatedUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCustomerExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereIntegrity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereProfessionalism($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereUpdatedAt($value)
 */
	class Indicator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\InterviewSchedule
 *
 * @property int $id
 * @property int $candidate
 * @property int $employee
 * @property string $date
 * @property string $time
 * @property string|null $comment
 * @property string|null $employee_response
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JobApplication|null $applications
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereCandidate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereEmployeeResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InterviewSchedule whereUpdatedAt($value)
 */
	class InterviewSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IpRestrict
 *
 * @property int $id
 * @property string $ip
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict query()
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IpRestrict whereUpdatedAt($value)
 */
	class IpRestrict extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Job
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $requirement
 * @property int $branch
 * @property int $category
 * @property string|null $skill
 * @property int|null $position
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $status
 * @property string|null $applicant
 * @property string|null $visibility
 * @property string|null $code
 * @property string|null $custom_question
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @property-read \App\Models\JobCategory|null $categories
 * @property-read \App\Models\User|null $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Job query()
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereCustomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereRequirement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Job whereVisibility($value)
 */
	class Job extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\JobApplication
 *
 * @property int $id
 * @property int $job
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $profile
 * @property string|null $resume
 * @property string|null $cover_letter
 * @property string|null $dob
 * @property string|null $gender
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property int $stage
 * @property int $order
 * @property string|null $skill
 * @property int $rating
 * @property int $is_archive
 * @property string|null $custom_question
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Job|null $jobs
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCoverLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCustomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereIsArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereUpdatedAt($value)
 */
	class JobApplication extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\JobApplicationNote
 *
 * @property int $id
 * @property int $application_id
 * @property int $note_created
 * @property string|null $note
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $noteCreated
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereNoteCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplicationNote whereUpdatedAt($value)
 */
	class JobApplicationNote extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\JobCategory
 *
 * @property int $id
 * @property string $title
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobCategory whereUpdatedAt($value)
 */
	class JobCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\JobOnBoard
 *
 * @property int $id
 * @property int $application
 * @property string|null $joining_date
 * @property string|null $status
 * @property int $convert_to_employee
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JobApplication|null $applications
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereApplication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereConvertToEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobOnBoard whereUpdatedAt($value)
 */
	class JobOnBoard extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\JobStage
 *
 * @property int $id
 * @property string $title
 * @property int $order
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobStage whereUpdatedAt($value)
 */
	class JobStage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LandingPageSection
 *
 * @property int $id
 * @property string $section_name
 * @property int $section_order
 * @property string|null $content
 * @property string $section_type
 * @property string $default_content
 * @property string $section_demo_image
 * @property string $section_blade_file_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereDefaultContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereSectionBladeFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereSectionDemoImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereSectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereSectionOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereSectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandingPageSection whereUpdatedAt($value)
 */
	class LandingPageSection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LateCharge
 *
 * @property-read \App\Models\AttendanceEmployee|null $attendance
 * @method static \Illuminate\Database\Eloquent\Builder|LateCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LateCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LateCharge query()
 */
	class LateCharge extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Leave
 *
 * @property int $id
 * @property int $employee_id
 * @property int $leave_type_id
 * @property string $applied_on
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $total_leave_days
 * @property string $leave_reason
 * @property string|null $remark
 * @property string $status
 * @property int $addressed_to
 * @property string|null $acc
 * @property string|null $parent
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employees
 * @property-read \App\Models\LeaveType|null $leaveType
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave query()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereAddressedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereAppliedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereLeaveReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereLeaveTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereTotalLeaveDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereUpdatedAt($value)
 */
	class Leave extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LeaveType
 *
 * @property int $id
 * @property string $title
 * @property int $days
 * @property string|null $parent
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $select_all
 * @property int $reduction
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereSelectAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereUpdatedAt($value)
 */
	class LeaveType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Loan
 *
 * @property int $id
 * @property int $employee_id
 * @property int $loan_option
 * @property string $title
 * @property int $amount
 * @property string|null $type
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereUpdatedAt($value)
 */
	class Loan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LoanOption
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanOption whereUpdatedAt($value)
 */
	class LoanOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Meeting
 *
 * @property int $id
 * @property int $branch_id
 * @property string $department_id
 * @property string $employee_id
 * @property string $title
 * @property string $date
 * @property string $time
 * @property string|null $note
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereUpdatedAt($value)
 */
	class Meeting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MeetingEmployee
 *
 * @property int $id
 * @property int $meeting_id
 * @property int $employee_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingEmployee whereUpdatedAt($value)
 */
	class MeetingEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string $messages
 * @property string|null $details
 * @property string|null $users
 * @property string|null $role
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereMessages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUsers($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NotificationEmployee
 *
 * @property int $id
 * @property int $notification_id
 * @property int $user_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationEmployee whereUserId($value)
 */
	class NotificationEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $order_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $card_number
 * @property string|null $card_exp_month
 * @property string|null $card_exp_year
 * @property string $plan_name
 * @property int $plan_id
 * @property float $price
 * @property string $price_currency
 * @property string $txn_id
 * @property string $payment_status
 * @property string $payment_type
 * @property string|null $receipt
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserCoupon|null $total_coupon_used
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCardExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCardExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePlanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePriceCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTxnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OtherPayment
 *
 * @property int $id
 * @property int $employee_id
 * @property string $title
 * @property int $amount
 * @property string|null $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherPayment whereUpdatedAt($value)
 */
	class OtherPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Overtime
 *
 * @property int $id
 * @property int $employee_id
 * @property string $title
 * @property int $number_of_days
 * @property int $hours
 * @property int $rate
 * @property string|null $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime query()
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overtime whereUpdatedAt($value)
 */
	class Overtime extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OvertimeAttendance
 *
 * @property int $id
 * @property int $applicant
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string $duration
 * @property string $status
 * @property string $date
 * @property string|null $approved_date
 * @property string $overtime_date
 * @property string $aggrement
 * @property string $notes
 * @property string|null $picture_in
 * @property string|null $picture_out
 * @property int $compensation_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OvertimeCompensation|null $compensation
 * @property-read \App\Models\Employee|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereAggrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereCompensationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereOvertimeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance wherePictureIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance wherePictureOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeAttendance whereUpdatedAt($value)
 */
	class OvertimeAttendance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OvertimeCompensation
 *
 * @property int $id
 * @property string $name
 * @property string $attendance_option
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereAttendanceOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeCompensation whereUpdatedAt($value)
 */
	class OvertimeCompensation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OvertimeEmployee
 *
 * @property int $id
 * @property int $overtime_id
 * @property int $employees_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employee
 * @property-read \App\Models\OvertimeAttendance|null $overtime
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereEmployeesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereOvertimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OvertimeEmployee whereUpdatedAt($value)
 */
	class OvertimeEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PayShift
 *
 * @property int $id
 * @property int $employee_id
 * @property int $shift_id
 * @property int $amount
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayShift whereUpdatedAt($value)
 */
	class PayShift extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaySlip
 *
 * @property int $id
 * @property int $employee_id
 * @property int $net_payble
 * @property string $salary_month
 * @property int $status
 * @property int $basic_salary
 * @property int $consumption_fee
 * @property string $allowance
 * @property string $commission
 * @property string $loan
 * @property string $saturation_deduction
 * @property string $other_payment
 * @property string $overtime
 * @property string|null $payshift
 * @property int|null $group_id
 * @property int|null $year_service
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employees
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereConsumptionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereLoan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereNetPayble($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereOtherPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereOvertime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip wherePayshift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereSalaryMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereSaturationDeduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaySlip whereYearService($value)
 */
	class PaySlip extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payees
 *
 * @property int $id
 * @property string $payee_name
 * @property string $contact_number
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payees newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payees newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payees query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payees whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payees whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payees whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payees whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payees wherePayeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payees whereUpdatedAt($value)
 */
	class Payees extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payer
 *
 * @property int $id
 * @property string $payer_name
 * @property string $contact_number
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payer whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payer wherePayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payer whereUpdatedAt($value)
 */
	class Payer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereUpdatedAt($value)
 */
	class PaymentType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PayslipType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayslipType whereUpdatedAt($value)
 */
	class PayslipType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Performance_Type
 *
 * @property int $id
 * @property string $name
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Competencies[] $types
 * @property-read int|null $types_count
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Performance_Type whereUpdatedAt($value)
 */
	class Performance_Type extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PermissionType
 *
 * @property int $id
 * @property string $title
 * @property int $days
 * @property string|null $many_submission
 * @property string $clock_out
 * @property string $get_consumption_fee
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereGetConsumptionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereManySubmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionType whereUpdatedAt($value)
 */
	class PermissionType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $duration
 * @property int $max_users
 * @property int $max_employees
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMaxEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMaxUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 */
	class Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PlanRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $plan_id
 * @property string $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plan|null $plan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanRequest whereUserId($value)
 */
	class PlanRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Position
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PositionGroup
 *
 * @property int $id
 * @property string $position_id
 * @property string $group_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PositionGroup whereUpdatedAt($value)
 */
	class PositionGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Promotion
 *
 * @property int $id
 * @property int $employee_id
 * @property int $designation_id
 * @property string $promotion_title
 * @property string $promotion_date
 * @property string $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion wherePromotionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion wherePromotionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereUpdatedAt($value)
 */
	class Promotion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Qrtoken
 *
 * @property int $id
 * @property string $token
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken query()
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qrtoken whereUpdatedAt($value)
 */
	class Qrtoken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Resignation
 *
 * @property int $id
 * @property int $employee_id
 * @property string $notice_date
 * @property string $resignation_date
 * @property string $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereNoticeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereResignationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resignation whereUpdatedAt($value)
 */
	class Resignation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoomType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereUpdatedAt($value)
 */
	class RoomType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SaturationDeduction
 *
 * @property int $id
 * @property int $employee_id
 * @property int $deduction_option
 * @property string $title
 * @property int $amount
 * @property string|null $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction query()
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereDeductionOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SaturationDeduction whereUpdatedAt($value)
 */
	class SaturationDeduction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property int|null $shift_id
 * @property int|null $room_id
 * @property string $employee_id
 * @property string|null $day
 * @property string|null $date
 * @property string $month
 * @property int|null $day_on_month
 * @property string|null $repeat
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RoomType|null $room
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereDayOnMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ScheduleEmployee
 *
 * @property int $id
 * @property int $shift_id
 * @property int $employee_id
 * @property string|null $date
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEmployee whereUpdatedAt($value)
 */
	class ScheduleEmployee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SetSalary
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary query()
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetSalary whereUpdatedAt($value)
 */
	class SetSalary extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Shift
 *
 * @property int $id
 * @property string $name
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereUpdatedAt($value)
 */
	class Shift extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Termination
 *
 * @property int $id
 * @property int $employee_id
 * @property string $notice_date
 * @property string $termination_date
 * @property string $termination_type
 * @property string $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Termination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Termination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Termination query()
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereNoticeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereTerminationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Termination whereUpdatedAt($value)
 */
	class Termination extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TerminationType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerminationType whereUpdatedAt($value)
 */
	class TerminationType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property string $title
 * @property int $employee_id
 * @property string $priority
 * @property string $end_date
 * @property string|null $description
 * @property string $ticket_code
 * @property int $ticket_created
 * @property int $created_by
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTicketCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTicketCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TicketReply
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $employee_id
 * @property string $description
 * @property int $created_by
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUpdatedAt($value)
 */
	class TicketReply extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TimeSheet
 *
 * @property int $id
 * @property int $employee_id
 * @property string $date
 * @property float $hours
 * @property string|null $remark
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $employee
 * @property-read \App\Models\Employee|null $employees
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeSheet whereUpdatedAt($value)
 */
	class TimeSheet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Trainer
 *
 * @property int $id
 * @property string $branch
 * @property string $firstname
 * @property string $lastname
 * @property string $contact
 * @property string $email
 * @property string|null $address
 * @property string|null $expertise
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereExpertise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereUpdatedAt($value)
 */
	class Trainer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Training
 *
 * @property int $id
 * @property int $branch
 * @property int $trainer_option
 * @property int $training_type
 * @property int $trainer
 * @property float $training_cost
 * @property int $employee
 * @property string $start_date
 * @property string $end_date
 * @property string|null $description
 * @property int $performance
 * @property int $status
 * @property string|null $remarks
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branches
 * @property-read \App\Models\Employee|null $employees
 * @property-read \App\Models\Trainer|null $trainers
 * @property-read \App\Models\TrainingType|null $types
 * @method static \Illuminate\Database\Eloquent\Builder|Training newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Training newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Training query()
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training wherePerformance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereTrainer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereTrainerOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereTrainingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereTrainingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Training whereUpdatedAt($value)
 */
	class Training extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TrainingType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingType whereUpdatedAt($value)
 */
	class TrainingType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transfer
 *
 * @property int $id
 * @property int $employee_id
 * @property int $branch_id
 * @property int $department_id
 * @property string $transfer_date
 * @property string $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereTransferDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereUpdatedAt($value)
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TransferBalance
 *
 * @property int $id
 * @property int $from_account_id
 * @property int $to_account_id
 * @property string $date
 * @property int $amount
 * @property int $payment_type_id
 * @property string|null $referal_id
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereFromAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereReferalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereToAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferBalance whereUpdatedAt($value)
 */
	class TransferBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Travel
 *
 * @property int $id
 * @property int $employee_id
 * @property string $start_date
 * @property string $end_date
 * @property string $purpose_of_visit
 * @property string $place_of_visit
 * @property string $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel wherePlaceOfVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel wherePurposeOfVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUpdatedAt($value)
 */
	class Travel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $type
 * @property int|null $role_id
 * @property string|null $fcm_token
 * @property string|null $token_company
 * @property string|null $avatar
 * @property string $lang
 * @property int|null $plan
 * @property string|null $plan_expire_date
 * @property int $requested_plan
 * @property string|null $last_login
 * @property int $is_active
 * @property string $created_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $messenger_color
 * @property int $dark_mode
 * @property int $active_status
 * @property-read \App\Models\Plan|null $currentPlan
 * @property-read \App\Models\Employee|null $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActiveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDarkMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMessengerColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlanExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRequestedPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserCoupon
 *
 * @property int $id
 * @property int $user
 * @property int $coupon
 * @property string|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Coupon|null $coupon_detail
 * @property-read \App\Models\User|null $userDetail
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCoupon whereUser($value)
 */
	class UserCoupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserEmailTemplate
 *
 * @property int $id
 * @property int $template_id
 * @property int $user_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailTemplate whereUserId($value)
 */
	class UserEmailTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Utility
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Utility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Utility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Utility query()
 */
	class Utility extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Warning
 *
 * @property int $id
 * @property int $warning_to
 * @property int $warning_by
 * @property string $subject
 * @property string $warning_date
 * @property string $description
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Warning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warning query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereWarningBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereWarningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warning whereWarningTo($value)
 */
	class Warning extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ZoomMeeting
 *
 * @property int $id
 * @property string|null $title
 * @property string $meeting_id
 * @property string $user_id
 * @property string|null $password
 * @property string $start_date
 * @property int $duration
 * @property string|null $start_url
 * @property string|null $join_url
 * @property string|null $status
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereJoinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereStartUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomMeeting whereUserId($value)
 */
	class ZoomMeeting extends \Eloquent {}
}


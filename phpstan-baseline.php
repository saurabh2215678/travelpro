<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'AccommodationDefaultLocation\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'AccommodationLocation\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'AccommodationRooms\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'accommodationCategories\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'accommodationDestination\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accomodation_info on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$slug in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method App\\\\Helpers\\\\CustomHelper\\:\\:sendEmail\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\AccountController\\:\\:\\$fbAppId is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\AccountController\\:\\:\\$fbAppSecret is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Static method App\\\\Helpers\\\\CustomHelper\\:\\:UploadFile\\(\\) invoked with 9 parameters, 2\\-3 required\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$IMG_HEIGHT in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$IMG_WIDTH in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$THUMB_HEIGHT in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$THUMB_WIDTH in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$isSent might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$user in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$userDetails in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$userEmail in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$userId on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/AccountController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\AccommodationController\\:\\:\\$limit\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\AccommodationRoom\\:\\:\\$roomAccommodationType\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'accommodationDestination\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'planData\' is not found in App\\\\Models\\\\AccommodationRoom model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'roomAccommodation\' is not found in App\\\\Models\\\\AccommodationRoom model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$title$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_room in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_room might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_route_group_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$group_data on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$isSaved might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sub_module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$title might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AccommodationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method find\\(\\) on an unknown class App\\\\Models\\\\ActionLog\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ActionLogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method orderBy\\(\\) on an unknown class App\\\\Models\\\\ActionLog\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ActionLogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'roles\' is not found in App\\\\Models\\\\Admin model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AdminController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AdminController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AdminController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AdminController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AirportMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\AirportMasterController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/AirportMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\BannerController\\:\\:\\$currentUrl is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\BannerController\\:\\:\\$page_arr is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$banner_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$is_deleted might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$video in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerImageGalleryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between int\\<1, max\\> and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerImageGalleryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$banner_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerImageGalleryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerImageGalleryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BannerImageGalleryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page_heading might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property Illuminate\\\\Contracts\\\\Auth\\\\Authenticatable\\:\\:\\$id\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Http\\\\Controllers\\\\Admin\\\\BlogController\\:\\:__construct\\(\\) with return type void returns Illuminate\\\\Http\\\\RedirectResponse but should not return anything\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'BlogsCat\' is not found in App\\\\Models\\\\Blog model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'blogToCategory\' is not found in App\\\\Models\\\\Blog model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$blog_img in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\CabCitiesController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabCitiesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\CabCitiesController\\:\\:\\$limit\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabCitiesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cabCityQuery might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabCitiesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabCitiesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabCitiesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\CabMasterController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\CabMasterController\\:\\:\\$limit\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_route_group_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$whereDate might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\CabRoute\\:\\:\\$title\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\DiscountModuleToGroup\\:\\:\\$discount_category\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property object\\:\\:\\$banner_image\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method App\\\\Http\\\\Controllers\\\\Admin\\\\CabRouteController\\:\\:delete_images\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method find\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\Admin\\\\CmsPages\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getCabData\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabRouteGroup\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabRouteToGroup\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CabRouteController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\City\\:\\:\\$cityState\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CityController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CmsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CmsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CmsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CmsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CmsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$flags of function json_encode expects int, true given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ContactEnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CouponController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$is_deleted might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CouponController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CouponController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/CouponController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Destination\\:\\:\\$destinationLocations\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Destination\\|Illuminate\\\\Database\\\\Eloquent\\\\Collection\\<int, App\\\\Models\\\\Destination\\>\\:\\:\\$destinationLocations\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between int\\<1, max\\> and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'destinationLocations\' is not found in App\\\\Models\\\\Destination model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$des_type in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$destination_id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$is_exist in empty\\(\\) always exists and is always falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sub_module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\DownloadController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\DownloadController\\:\\:\\$limit\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Http\\\\Controllers\\\\Admin\\\\DownloadController\\:\\:saveDocuments\\(\\) invoked with 3 parameters, 2 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/DownloadController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\EmailTeamplateController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EmailTeamplateController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\EmailTeamplateController\\:\\:\\$limit\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EmailTeamplateController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiriesMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\EnquiriesMasterController\\:\\:\\$ADMIN_ROUTE_NAME is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiriesMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'GroupData\' is not found in App\\\\Models\\\\EnquiriesMaster model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiriesMasterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\EnquiriesMasterGroupController\\:\\:\\$ADMIN_ROUTE_NAME is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiriesMasterGroupController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 8,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'Accommodation\' is not found in App\\\\Models\\\\Enquiry model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'Destination\' is not found in App\\\\Models\\\\Enquiry model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'EnquiryForType\' is not found in App\\\\Models\\\\Enquiry model\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'EnquiryInteraction\' is not found in App\\\\Models\\\\Enquiry model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'SubDestination\' is not found in App\\\\Models\\\\Enquiry model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'accommodationDestination\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_ids in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$destination_ids in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$emailTemplate in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$enquiry_for_ids in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$getLeadStatusSlug in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EnquiryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method App\\\\Helpers\\\\CustomHelper\\:\\:recordActionLog\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$value of function count expects array\\|Countable, App\\\\Models\\\\Event given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_description might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_table might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_type might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$description might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$function_name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$row_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/EventController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\Admin\\\\CourseCategory\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\FaqController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\FaqController\\:\\:\\$limit\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'faqParentDestination\' is not found in App\\\\Models\\\\Faq model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$category in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$category in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tbl_category in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tbl_id in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tbl_id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tbl_name in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\Admin\\\\PaymentGateway\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FlightApiController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$msg might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FlightApiController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payment_gateways_id might not be defined\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FlightApiController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$updated_status on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FlightApiController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Form\\:\\:\\$eventID\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$enquiry might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormdataController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$formElements might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormdataController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$name in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/FormdataController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$back_url in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$back_url in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method App\\\\Models\\\\ImageCategory\\|Illuminate\\\\Database\\\\Eloquent\\\\Collection\\<int, App\\\\Models\\\\ImageCategory\\>\\:\\:videos\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ImageController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Static property App\\\\Http\\\\Controllers\\\\Admin\\\\ImageController\\:\\:\\$_invalidCharacters is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$child_table_id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ImageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/LoginController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/LoginController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_template in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/LoginController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\LoginHistoryController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/LoginHistoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MasterEnquiriesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\MasterEnquiriesController\\:\\:\\$ADMIN_ROUTE_NAME is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MasterEnquiriesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\MasterEnquiriesController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MasterEnquiriesController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'GroupData\' is not found in App\\\\Models\\\\EnquiriesMaster model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MasterEnquiriesController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MediaController.php',
];
$ignoreErrors[] = [
	'message' => '#^Offset \'caption\' on array\\{caption\\: string\\} on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MediaController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$keyword on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MediaController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$type on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MediaController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Models\\\\Category\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MenuController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MenuController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MenuController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\MetaTagsController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page_heading might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/MetaTagsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ModuleController\\:\\:\\$ADMIN_ROUTE_NAME is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ModuleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ModuleController\\:\\:\\$limit is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ModuleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\NewsletterController\\:\\:\\$ADMIN_ROUTE_NAME is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/NewsletterController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'Country\' is not found in App\\\\Models\\\\Order model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'Package\' is not found in App\\\\Models\\\\Order model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'orderData\' is not found in App\\\\Models\\\\OrderPayments model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$subject_params$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$content in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$order_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$order_id on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$subject_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$subject_params in isset\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$trip_date on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/OrderController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PackageCategoryController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PackageCategoryController\\:\\:\\$limit\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PackageController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 40,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PackageController\\:\\:\\$limit\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Flag\\:\\:\\$package_type\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Package\\|Illuminate\\\\Database\\\\Eloquent\\\\Collection\\<int, App\\\\Models\\\\Package\\>\\:\\:\\$packagePriceCategory\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property object\\:\\:\\$is_default_without_price\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$title on array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Offset \'message\' on array\\{success\\: false, message\\: \'\'\\} in empty\\(\\) always exists and is always falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\PackagePriceInfo\\:\\:\\$is_default \\(bool\\) does not accept int\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'PackageDepartureCapacity\' is not found in App\\\\Models\\\\PackageDeparture model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageCategories\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageFlags\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageParentDestination\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$inclusions_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$isSaved might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_id might not be defined\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 11,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$packageAccommodations in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$packageExpertsData might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$packageFlagsData might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$packageThemesData might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_id in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$price_id might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$removed_ids in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sub_module_desc might not be defined\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$url might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$msg might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PaymentGatewayController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payment_gateways_id might not be defined\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PaymentGatewayController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$updated_status on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PaymentGatewayController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PriceCategoryController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\PriceCategoryController\\:\\:\\$limit\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between int\\<1, max\\> and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$is_deleted might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$price_categories_id might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/PriceCategoryController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$amount in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$balance might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$comment on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$content in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payment_method on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone_no on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$type on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RegisterUserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'reviewProduct\' is not found in App\\\\Models\\\\Review model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ReviewController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'reviewUser\' is not found in App\\\\Models\\\\Review model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ReviewController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\RoleController\\:\\:\\$module_data\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Called \'count\' on Laravel collection, but could have been retrieved as a query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\RoleController\\:\\:\\$module_id is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\RoleController\\:\\:\\$module_key is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/RoleController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Setting\\:\\:\\$default_value\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Setting\\:\\:\\$description\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$id on string\\|false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$isSent might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SettingsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\SitemapController\\:\\:\\$limit\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SitemapController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class App\\\\Models\\\\Sitemap\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SitemapController_27_02_23.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method find\\(\\) on an unknown class App\\\\Models\\\\Sitemap\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SitemapController_27_02_23.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\SmsTeamplateController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SmsTeamplateController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\SmsTeamplateController\\:\\:\\$limit\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SmsTeamplateController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Laravel\\\\Socialite\\\\Contracts\\\\Provider\\:\\:stateless\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SocialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\TempUser\\:\\:\\$is_agent \\(bool\\|null\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SocialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\TempUser\\:\\:\\$is_verified \\(bool\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SocialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\TempUser\\:\\:\\$status \\(bool\\|null\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/SocialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Team\\:\\:\\$image\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Team\\:\\:\\$title\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Team\\|Illuminate\\\\Database\\\\Eloquent\\\\Collection\\<int, App\\\\Models\\\\Team\\>\\:\\:\\$image\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method App\\\\Models\\\\Team\\:\\:teamToDomains\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method App\\\\Helpers\\\\CustomHelper\\:\\:recordActionLog\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$value of function count expects array\\|Countable, App\\\\Models\\\\Team given\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'teamDomains\' is not found in App\\\\Models\\\\Team model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_description might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_table might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_type might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$description might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$function_name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pdf in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$row_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\TeamManagementController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\TeamManagementController\\:\\:\\$limit\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TeamManagementController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method convert\\(\\) on an unknown class App\\\\Libraries\\\\CurrencyConverter\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method download\\(\\) on an unknown class Maatwebsite\\\\Excel\\\\Facades\\\\Excel\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method get\\(\\) on an unknown class Illuminate\\\\Support\\\\Facades\\\\Input\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method query\\(\\) on an unknown class App\\\\Models\\\\Product\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Models\\\\Product\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>\\=" between int\\<0, max\\>\\|false and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class App\\\\Exports\\\\ProductExport not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class App\\\\Libraries\\\\CurrencyConverter not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ThemeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ThemeController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ThemeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ThemeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ThemeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ThemeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Called \'pluck\' on Laravel collection, but could have been retrieved as a query\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 9,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ToolsController\\:\\:\\$ADMIN_ROUTE_NAME is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\ToolsController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_categories in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodation_features in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$attachments in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$blog_categories in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$common_images in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$flights in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$highlights in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image_name in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$itenaries in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_airlines in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_inclusions in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_price_destination_hotels in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_prices in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_prices in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_to_themes in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_types in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$price_categories in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$price_category_elements in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$resources in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$results in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$themes in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/ToolsController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'agentGroup\' is not found in App\\\\Models\\\\User model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'agentInfoSearch\' is not found in App\\\\Models\\\\User model\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Static method App\\\\Helpers\\\\CustomHelper\\:\\:UploadFile\\(\\) invoked with 9 parameters, 2\\-3 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$storage$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$FRONTEND_LOGO in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$amount in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$balance might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$comment on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$content in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$last_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payment_method on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone_no on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$type on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserAgentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method recordActionLog\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\Admin\\\\CustomHelper\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Left side of && is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$id$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_description might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_table might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$action_type might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$description might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$function_name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$is_updated might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$row_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Array has 2 duplicate keys with value \'remarks\' \\(\'remarks\', \'remarks\'\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Array has 2 duplicate keys with value \'trip_type\' \\(\'trip_type\', \'trip_type\'\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$name on string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\Admin\\\\VoucherController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$attachments in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$created_at on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$driver_html on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$itinerary on left side of \\?\\? is never defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_data on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pickup_details on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$trip_date on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$user_phone on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/VoucherController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\WidgetController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/WidgetController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\Admin\\\\WidgetController\\:\\:\\$limit\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/WidgetController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between 1 and 0 is always true\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/WidgetController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$module_desc might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/WidgetController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$new_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/Admin/WidgetController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\BlogTag\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'blogToCategory\' is not found in App\\\\Models\\\\Blog model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'tag\' is not found in App\\\\Models\\\\Blog model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$blogDetails in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$categories_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$category in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$category on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$slug in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BlogController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Order\\:\\:\\$Package\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Order\\:\\:\\$agent_id\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$ITC on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$TPSLTxnID on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$accountNo on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$amount on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$bankCode on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$currencyCode on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$custId on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$customerName on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$iv on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$iv on an unknown class TransactionResponseBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$key on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$key on an unknown class TransactionResponseBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$merchantCode on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$merchantTxnRefNumber on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$mobileNumber on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$requestType on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$returnURL on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$shoppingCartDetails on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$timeOut on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$txnDate on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to property \\$webServiceLocator on an unknown class TransactionRequestBean\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method getResponsePayload\\(\\) on an unknown class TransactionResponseBean\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method getTransactionToken\\(\\) on an unknown class TransactionRequestBean\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to method setResponsePayload\\(\\) on an unknown class TransactionResponseBean\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method PostDataByEndPoint\\(\\) on an unknown class App\\\\Helpers\\\\FlightHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getCabRoutePrice\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Elseif condition is always false\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Elseif condition is always true\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 12,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always false\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class TransactionRequestBean not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class TransactionResponseBean not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Negated boolean expression is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function sha1 expects string, int given\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$flags of function json_encode expects int, true given\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\Order\\:\\:\\$payment_status \\(bool\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\OrderPayments\\:\\:\\$order_id \\(string\\|null\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\OrderPayments\\:\\:\\$status \\(string\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageSlot\' is not found in App\\\\Models\\\\PackageInventory model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$item_number$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$request$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$actual_amt in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$adult in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$amount on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_id might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_route_group_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_route_id might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$city on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$content in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$destination on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$duration on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$end_time on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$enquiry on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$gst_info on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$hold_ticket might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$order_addresses on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$order_id on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$order_phone on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$p_status on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_id might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_type might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$package_type_name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payDetails might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$payment might not be defined\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$priceIds might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$request in isset\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$start_time might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$start_time on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sub_total_amount on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$subject_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$totalSsrBaggage might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$totalSsrMeal might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$total_amount on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$total_pax might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$total_pax on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$traveller on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tripInfos might not be defined\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tripType might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/BookingController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\CabRoute\\:\\:\\$CabRouteToGroup\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\CabRoute\\:\\:\\$CabRouteinventory\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getAPIBaseUrl\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getCabData\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getCabRoutePrice\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getMenu\\(\\) on an unknown class App\\\\Helpers\\\\CabHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 17,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Http\\\\Controllers\\\\CabController\\:\\:__construct\\(\\) with return type void returns Illuminate\\\\Http\\\\RedirectResponse but should not return anything\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabData\' is not found in App\\\\Models\\\\CabRoutePrice model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabRouteGroup\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabRouteinventory\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'originCity\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 9,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageFlags\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packagePrices\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Ternary operator condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_ids might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$exclusion on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$inclusion on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$places on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$privacy_link on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sightseening_name on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$term on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$terms_service_link on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CabController.php',
];
$ignoreErrors[] = [
	'message' => '#^Function captcha_src not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CommonController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$options might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CommonController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$packageSubDestination in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/CommonController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/CronController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$destinationDetails in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/DestinationController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$faq_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$slug in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FaqController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method PostDataByEndPoint\\(\\) on an unknown class App\\\\Helpers\\\\FlightHelper\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getAPIBaseUrl\\(\\) on an unknown class App\\\\Helpers\\\\FlightHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getMenu\\(\\) on an unknown class App\\\\Helpers\\\\FlightHelper\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Comparison operation "\\>" between int\\<1, max\\> and 0 is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Method App\\\\Http\\\\Controllers\\\\FlightController\\:\\:__construct\\(\\) with return type void returns Illuminate\\\\Http\\\\RedirectResponse but should not return anything\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageFlags\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packagePrices\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$dob_key might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$tripInfos might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/FlightController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/FormsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Offset \'form_id\' on non\\-empty\\-array on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FormsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\FormsController\\:\\:\\$limit is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FormsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$validations in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/FormsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method App\\\\Helpers\\\\CustomHelper\\:\\:sendEmail\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Empty array passed to foreach\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$flags of function json_encode expects int, true given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Unreachable statement \\- code above always terminates\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$blogArr in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$celebrities_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$hpp in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/HomeController.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method where\\(\\) on an unknown class App\\\\Http\\\\Controllers\\\\BlogTag\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/NewsController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Package\\:\\:\\$packageDestination\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\PackageAccommodation\\:\\:\\$Accommodation\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\PackageAccommodation\\:\\:\\$Itenary\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'PackageDeparture\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageCategories\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageDestination\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageFlags\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packagePrices\' is not found in App\\\\Models\\\\Package model\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'packageSlot\' is not found in App\\\\Models\\\\PackageInventory model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$accommodations in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$categories_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$destinations_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$filter_month_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$filter_package_budget_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$filter_tour_type_arr in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$getDestination in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$icon might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$testimonials_data in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PackageController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Order\\:\\:\\$agent_id\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Http\\\\Controllers\\\\PaymentController\\:\\:\\$limit is unused\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Property App\\\\Models\\\\Order\\:\\:\\$payment_status \\(bool\\) does not accept int\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$country_code might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$fees on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/PaymentController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$slug in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$teamDetails in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TeamController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$savedId on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$slug in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$testimonialDetails in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/TestimonialController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\UserController\\:\\:\\$ADMIN_ROUTE_NAME\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Http\\\\Controllers\\\\UserController\\:\\:\\$limit\\.$#',
	'count' => 9,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\DiscountModuleToGroup\\:\\:\\$discount_category\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property App\\\\Models\\\\Order\\|Illuminate\\\\Database\\\\Eloquent\\\\Collection\\<int, App\\\\Models\\\\Order\\>\\:\\:\\$agent_id\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Array has 2 duplicate keys with value \'trip_type\' \\(\'trip_type\', \'trip_type\'\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Expression on left side of \\?\\? is not nullable\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^If condition is always true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'CabRouteGroup\' is not found in App\\\\Models\\\\CabRoute model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'Package\' is not found in App\\\\Models\\\\Order model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'accommodationDestination\' is not found in App\\\\Models\\\\Accommodation model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Relation \'roomAccommodation\' is not found in App\\\\Models\\\\AccommodationRoom model\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Static method App\\\\Helpers\\\\CustomHelper\\:\\:UploadFile\\(\\) invoked with 9 parameters, 2\\-3 required\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$storage$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$FRONTEND_LOGO in empty\\(\\) is never defined\\.$#',
	'count' => 8,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$IMG_HEIGHT in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$IMG_WIDTH in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$THUMB_HEIGHT in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$THUMB_WIDTH in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$attachments in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cab_route_group_id in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$content in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$created_at on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$email_params in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$isSaved might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$itinerary on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$phone on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pickup_details on left side of \\?\\? is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_arr in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sms_content_data in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$title in empty\\(\\) is never defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$trip_date on left side of \\?\\? always exists and is not nullable\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$userId in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/app/Http/Controllers/UserController.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];

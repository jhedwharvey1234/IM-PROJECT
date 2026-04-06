<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/register', 'Auth::register');
$routes->post('/auth/store', 'Auth::store');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/entra/login', 'EntraAuthController::login');
$routes->get('/auth/entra/callback', 'EntraAuthController::callback');
$routes->get('/auth/entra/logout', 'EntraAuthController::logout');
$routes->get('/dashboard', 'Dashboard::index');

$routes->get('/users', 'UserController::index');
$routes->get('/users/create', 'UserController::create');
$routes->post('/users/store', 'UserController::store');
$routes->get('/users/details/(:segment)', 'UserController::details/$1');
$routes->get('/users/edit/(:num)', 'UserController::edit/$1');
$routes->post('/users/update/(:num)', 'UserController::update/$1');
$routes->get('/users/delete/(:num)', 'UserController::delete/$1');
$routes->get('/users/search', 'UserController::search');
$routes->post('/users/toggleSync/(:num)', 'UserController::toggleSync/$1');
$routes->post('/users/sync-entra', 'UserController::syncEntra');
$routes->get('/users/sync-entra-stream', 'UserController::syncEntraStream');
$routes->post('/users/sync-entra-cancel', 'UserController::cancelEntraSync');

$routes->get('/assets', 'AssetController::index');
$routes->get('/assets/create', 'AssetController::create');
$routes->post('/assets/store', 'AssetController::store');
$routes->get('/assets/edit/(:num)', 'AssetController::edit/$1');
$routes->post('/assets/update/(:num)', 'AssetController::update/$1');
$routes->get('/assets/delete/(:num)', 'AssetController::delete/$1');
$routes->post('/assets/batch-delete', 'AssetController::batchDelete');
$routes->get('/assets/details/(:num)', 'AssetController::details/$1');
$routes->get('/assets/export-pdf/(:num)', 'AssetController::exportPdf/$1');
$routes->get('/assets/search', 'AssetController::search');
$routes->get('/assets/public/(:segment)', 'AssetPublicController::details/$1');
$routes->post('/assets/note/add', 'AssetController::addNote');
$routes->get('/assets/note/delete/(:num)', 'AssetController::deleteNote/$1');

$routes->get('/assets/item/edit/(:num)', 'AssetController::editItem/$1');
$routes->post('/assets/item/update/(:num)', 'AssetController::updateItem/$1');
$routes->get('/assets/item/delete/(:num)', 'AssetController::deleteItem/$1');
$routes->post('/assets/item/store', 'AssetController::storeItem');

// Asset Peripheral routes
$routes->post('/assets/peripheral/store', 'AssetController::storePeripheral');
$routes->get('/assets/peripheral/edit/(:num)', 'AssetController::editPeripheral/$1');
$routes->post('/assets/peripheral/update/(:num)', 'AssetController::updatePeripheral/$1');
$routes->get('/assets/peripheral/delete/(:num)', 'AssetController::deletePeripheral/$1');

// Peripheral routes
$routes->get('/peripherals', 'PeripheralController::index');
$routes->get('/peripherals/create', 'PeripheralController::create');
$routes->post('/peripherals/store', 'PeripheralController::store');
$routes->get('/peripherals/edit/(:num)', 'PeripheralController::edit/$1');
$routes->post('/peripherals/update/(:num)', 'PeripheralController::update/$1');
$routes->get('/peripherals/delete/(:num)', 'PeripheralController::delete/$1');
$routes->get('/peripherals/details/(:num)', 'PeripheralController::details/$1');
$routes->get('/peripherals/search', 'PeripheralController::search');
$routes->get('/peripherals/getAssetDetails/(:num)', 'PeripheralController::getAssetDetails/$1');

// Units routes
$routes->get('/units', 'UnitController::index');
$routes->get('/units/create', 'UnitController::create');
$routes->post('/units/store', 'UnitController::store');
$routes->get('/units/view/(:num)', 'UnitController::view/$1');
$routes->get('/units/edit/(:num)', 'UnitController::edit/$1');
$routes->post('/units/update/(:num)', 'UnitController::update/$1');
$routes->get('/units/delete/(:num)', 'UnitController::delete/$1');

// Settings routes
$routes->get('/settings', 'SettingsController::index');

$routes->get('/settings/categories', 'SettingsController::categories');
$routes->post('/settings/save-category', 'SettingsController::saveCategory');
$routes->get('/settings/delete-category/(:num)', 'SettingsController::deleteCategory/$1');

$routes->get('/settings/technologies', 'SettingsController::technologies');
$routes->post('/settings/technologies/store', 'SettingsController::storeTechnology');
$routes->post('/settings/technologies/update/(:num)', 'SettingsController::updateTechnology/$1');
$routes->get('/settings/technologies/delete/(:num)', 'SettingsController::deleteTechnology/$1');

$routes->get('/settings/application-status', 'SettingsController::applicationStatus');
$routes->post('/settings/application-status/store', 'SettingsController::storeApplicationStatus');
$routes->post('/settings/application-status/update/(:num)', 'SettingsController::updateApplicationStatus/$1');
$routes->get('/settings/application-status/delete/(:num)', 'SettingsController::deleteApplicationStatus/$1');

$routes->get('/settings/servers', 'SettingsController::servers');
$routes->post('/settings/servers/store', 'SettingsController::storeServer');
$routes->post('/settings/servers/update/(:num)', 'SettingsController::updateServer/$1');
$routes->get('/settings/servers/delete/(:num)', 'SettingsController::deleteServer/$1');

$routes->get('/settings/environments', 'SettingsController::environments');
$routes->post('/settings/environments/store', 'SettingsController::storeEnvironment');
$routes->post('/settings/environments/update/(:num)', 'SettingsController::updateEnvironment/$1');
$routes->get('/settings/environments/delete/(:num)', 'SettingsController::deleteEnvironment/$1');

$routes->get('/settings/application-contacts', 'SettingsController::applicationContacts');
$routes->post('/settings/application-contacts/store', 'SettingsController::storeApplicationContact');
$routes->post('/settings/application-contacts/update/(:num)', 'SettingsController::updateApplicationContact/$1');
$routes->get('/settings/application-contacts/delete/(:num)', 'SettingsController::deleteApplicationContact/$1');

$routes->get('/settings/locations', 'LocationController::index');
$routes->get('/settings/locations/create', 'LocationController::create');
$routes->post('/settings/locations/store', 'LocationController::store');
$routes->get('/settings/locations/edit/(:num)', 'LocationController::edit/$1');
$routes->post('/settings/locations/update/(:num)', 'LocationController::update/$1');
$routes->get('/settings/locations/delete/(:num)', 'LocationController::delete/$1');

$routes->get('/settings/workstations', 'WorkstationController::index');
$routes->get('/settings/workstations/create', 'WorkstationController::create');
$routes->post('/settings/workstations/store', 'WorkstationController::store');
$routes->get('/settings/workstations/edit/(:num)', 'WorkstationController::edit/$1');
$routes->post('/settings/workstations/update/(:num)', 'WorkstationController::update/$1');
$routes->get('/settings/workstations/delete/(:num)', 'WorkstationController::delete/$1');

$routes->get('/settings/peripheral-types', 'PeripheralTypeController::index');
$routes->get('/settings/peripheral-types/create', 'PeripheralTypeController::create');
$routes->post('/settings/peripheral-types/store', 'PeripheralTypeController::store');
$routes->get('/settings/peripheral-types/edit/(:num)', 'PeripheralTypeController::edit/$1');
$routes->post('/settings/peripheral-types/update/(:num)', 'PeripheralTypeController::update/$1');
$routes->get('/settings/peripheral-types/delete/(:num)', 'PeripheralTypeController::delete/$1');

$routes->get('/settings/departments', 'DepartmentController::index');
$routes->get('/settings/departments/create', 'DepartmentController::create');
$routes->post('/settings/departments/store', 'DepartmentController::store');
$routes->get('/settings/departments/edit/(:num)', 'DepartmentController::edit/$1');
$routes->post('/settings/departments/update/(:num)', 'DepartmentController::update/$1');
$routes->get('/settings/departments/delete/(:num)', 'DepartmentController::delete/$1');

$routes->get('/settings/assigned-users', 'AssignableUserController::index');
$routes->get('/settings/assigned-users/create', 'AssignableUserController::create');
$routes->post('/settings/assigned-users/store', 'AssignableUserController::store');
$routes->get('/settings/assigned-users/edit/(:num)', 'AssignableUserController::edit/$1');
$routes->post('/settings/assigned-users/update/(:num)', 'AssignableUserController::update/$1');
$routes->get('/settings/assigned-users/delete/(:num)', 'AssignableUserController::delete/$1');

$routes->get('/settings/user-roles', 'UserRoleController::index');
$routes->get('/settings/user-roles/create', 'UserRoleController::create');
$routes->post('/settings/user-roles/store', 'UserRoleController::store');
$routes->get('/settings/user-roles/edit/(:num)', 'UserRoleController::edit/$1');
$routes->post('/settings/user-roles/update/(:num)', 'UserRoleController::update/$1');
$routes->get('/settings/user-roles/delete/(:num)', 'UserRoleController::delete/$1');

$routes->get('/settings/document-categories', 'SettingsController::documentCategories');
$routes->post('/settings/document-categories/store', 'SettingsController::storeDocumentCategory');
$routes->post('/settings/document-categories/update/(:num)', 'SettingsController::updateDocumentCategory/$1');
$routes->get('/settings/document-categories/delete/(:num)', 'SettingsController::deleteDocumentCategory/$1');

$routes->get('/settings/document-types', 'SettingsController::documentTypes');
$routes->post('/settings/document-types/store', 'SettingsController::storeDocumentType');
$routes->post('/settings/document-types/update/(:num)', 'SettingsController::updateDocumentType/$1');
$routes->get('/settings/document-types/delete/(:num)', 'SettingsController::deleteDocumentType/$1');

// Applications Management routes
$routes->get('/applications', 'ApplicationController::index');
$routes->get('/applications/create', 'ApplicationController::create');
$routes->post('/applications/store', 'ApplicationController::store');
$routes->get('/applications/edit/(:num)', 'ApplicationController::edit/$1');
$routes->post('/applications/update/(:num)', 'ApplicationController::update/$1');
$routes->get('/applications/delete/(:num)', 'ApplicationController::delete/$1');
$routes->get('/applications/details/(:num)', 'ApplicationController::details/$1');
$routes->get('/applications/search', 'ApplicationController::search');
$routes->post('/applications/related-data/store/(:num)', 'ApplicationController::storeRelatedData/$1');
$routes->post('/applications/related-data/update/(:num)/(:num)', 'ApplicationController::updateRelatedData/$1/$2');
$routes->get('/applications/related-data/delete/(:num)/(:num)', 'ApplicationController::deleteRelatedData/$1/$2');

// Document Management routes
$routes->get('/documents', 'DocumentController::index');
$routes->get('/documents/create', 'DocumentController::create');
$routes->post('/documents/store', 'DocumentController::store');
$routes->get('/documents/edit/(:num)', 'DocumentController::edit/$1');
$routes->post('/documents/update/(:num)', 'DocumentController::update/$1');
$routes->get('/documents/delete/(:num)', 'DocumentController::delete/$1');
$routes->post('/documents/batch-delete', 'DocumentController::batchDelete');
$routes->get('/documents/details/(:num)', 'DocumentController::details/$1');
$routes->post('/documents/note/store/(:num)', 'DocumentController::addNote/$1');
$routes->get('/documents/note/delete/(:num)/(:num)', 'DocumentController::deleteNote/$1/$2');
$routes->post('/documents/file/upload/(:num)', 'DocumentController::uploadFile/$1');
$routes->get('/documents/file/delete/(:num)/(:num)', 'DocumentController::deleteFile/$1/$2');
$routes->post('/documents/alert/store/(:num)', 'DocumentController::addAlert/$1');
$routes->get('/documents/alert/delete/(:num)/(:num)', 'DocumentController::deleteAlert/$1/$2');
$routes->get('/documents/alerts', 'DocumentController::alerts');

// Notifications routes
$routes->get('/notifications', 'NotificationController::index');

// DCF Management routes
$routes->get('/dcf', 'DcfController::index');
$routes->get('/dcf/create', 'DcfController::create');
$routes->post('/dcf/store', 'DcfController::store');
$routes->get('/dcf/details/(:num)', 'DcfController::details/$1');
$routes->get('/dcf/edit/(:num)', 'DcfController::edit/$1');
$routes->post('/dcf/update/(:num)', 'DcfController::update/$1');
$routes->get('/dcf/delete/(:num)', 'DcfController::delete/$1');
$routes->get('/dcf/questions', 'DcfController::questions');
$routes->get('/dcf/past-questions/(:num)', 'DcfController::pastQuestionsByDepartment/$1');
$routes->get('/dcf/parts', 'DcfController::parts');
$routes->get('/dcf/past-parts/(:num)', 'DcfController::pastPartsByDepartment/$1');
$routes->post('/dcf/upload-image', 'DcfController::uploadImage');

// DCF Public routes (no authentication required)
$routes->get('/dcf/form/(:segment)', 'DcfPublicController::form/$1');
$routes->get('/dcf/email-role/(:segment)', 'DcfPublicController::emailRole/$1');
$routes->post('/dcf/submit/(:segment)', 'DcfPublicController::submit/$1');


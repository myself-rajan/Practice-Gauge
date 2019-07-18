<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});

/*********USERS*********/

Breadcrumbs::for('viewUsers', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('viewUsers'));
});

Breadcrumbs::for('viewRoles', function ($trail) {
    $trail->parent('home');
    $trail->push('Users Roles', route('viewRoles'));
});


Breadcrumbs::for('general_settings', function ($trail) {
    $trail->parent('home');
    $trail->push('General Settings', route('general_settings'));
});

Breadcrumbs::for('qbo_integration', function ($trail) {
    $trail->parent('home');
    $trail->push('QBO Integration', route('qbo_integration'));
});


Breadcrumbs::for('settings_account_mapping', function ($trail) {
    $trail->parent('home');
    $trail->push('Accounts', route('available_accounts'));
    $trail->push('Account Mapping', route('settings_account_mapping'));
});


Breadcrumbs::for('account_mapping', function ($trail) {
    $trail->parent('home');
    $trail->push('Accounts', route('available_accounts'));
    $trail->push('Account Mapping', route('account_mapping'));
});

Breadcrumbs::for('available_practices', function ($trail) {
    $trail->parent('home');
    $trail->push('Practices', route('available_practices'));
});


Breadcrumbs::for('available_accounts', function ($trail) {
    $trail->parent('home');
    $trail->push('Accounts', route('available_accounts'));
});

Breadcrumbs::for('dashboard_reports', function ($trail) {
    $trail->parent('home');
    $trail->push('Reports', route('dashboard_reports'));
});

Breadcrumbs::for('practices_status', function ($trail) {
    $trail->parent('home');
    $trail->push('Status', route('practices_status'));
});

Breadcrumbs::for('view_roles', function ($trail) {
    $trail->parent('home');
    $trail->push('User Roles', route('view_roles'));
});

Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('users'));
});

Breadcrumbs::for('editUser', function ($trail) {
    $trail->parent('home');
    $trail->push('Edit User', route('editUser'));
});

Breadcrumbs::for('qbo_integration_sync', function ($trail) {
    $trail->parent('home');
    $trail->push('QBO Integration', route('qbo_integration_sync'));
});

Breadcrumbs::for('qbo_connect', function ($trail) {
    $trail->parent('home');
    $trail->push('QBO Connect', route('qbo_connect'));
});


// Breadcrumbs::for('user_details', function ($trail) {
//     $route_params = Route::getCurrentRoute()->parameters();
//     $trail->parent('user_list');
//     $trail->push('User Detail', route('user_details', $route_params['id']));
// });
<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Admin
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push( __('admin.dashboard'), route('admin.dashboard'));
});


/**
 * Categories
 */

Breadcrumbs::macro('resource', function (string $name, string $title) {
    // Admin > Categories
    Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
        $trail->parent('admin.dashboard');
        $trail->push($title, route("{$name}.index"));
    });

    // Admin > Categories > New
    Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push(__('admin.create'), route("{$name}.create"));
    });

    // Admin > Categories > Post 123
    Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push($model->name, route("{$name}.show", $model));
    });

    // Admin > Categories > Post 123 > Edit
    Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model) use ($name) {
        $trail->parent("{$name}.show", $model);
        $trail->push(__('admin.edit'), route("{$name}.edit", $model));
    });
});

Breadcrumbs::resource('admin.categories', __('admin.categories'));

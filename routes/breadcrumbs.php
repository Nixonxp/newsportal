<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Admin
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push( __('admin.dashboard'), route('admin.dashboard'));
});

// Subscribers
Breadcrumbs::for('admin.subscribes.index', function (BreadcrumbTrail $trail) {
    $trail->push( __('admin.subscribes'), route('admin.subscribes.index'));
});

// logs
Breadcrumbs::for('admin.logs.index', function (BreadcrumbTrail $trail) {
    $trail->push( __('admin.logs'), route('admin.logs.index'));
});

Breadcrumbs::for('admin.logs.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent("admin.logs.index");
    $trail->push( __('admin.element_detail'), route('admin.logs.show', $id));
});

/**
 * Resources
 */

Breadcrumbs::macro('resource', function (string $name, string $title, string $elementName) {
    // Admin > Elements
    Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
        $trail->parent('admin.dashboard');
        $trail->push($title, route("{$name}.index"));
    });

    // Admin > Elements > New
    Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push(__('admin.create'), route("{$name}.create"));
    });

    // Admin > Elements > Element 123
    Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model) use ($name, $elementName) {
        $trail->parent("{$name}.index");
        $trail->push($elementName, route("{$name}.show", $model));
    });

    // Admin > Elements > Element 123 > Edit
    Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model) use ($name) {
        $trail->parent("{$name}.show", $model);
        $trail->push(__('admin.edit'), route("{$name}.edit", $model));
    });
});

Breadcrumbs::resource('admin.categories', __('admin.categories'), __('admin.category_detail'));
Breadcrumbs::resource('admin.posts', __('admin.news'), __('admin.singlenews_detail'));
Breadcrumbs::resource('admin.users', __('admin.users'), __('admin.user_detail'));
Breadcrumbs::resource('admin.ads', __('admin.ads'), __('admin.ad_detail'));

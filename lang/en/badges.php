<?php

return [
    'title' => 'Badges',
    'menuName' => 'badge',
    'fields' => [
        'name' => 'Name',
        'description' => 'Description',
    ],
    'buttons' => [
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
    ],
    'messages' => [
        'created' => 'Badge created successfully.',
        'updated' => 'Badge updated successfully.',
        'deleted' => 'Badge deleted successfully.',
    ],
    'dialogs' => [
        'delete' => [
            'title' => 'Delete Badge',
            'message' => 'Are you sure you want to delete this badge?',
        ],
    ],
    'index' => [
        'title' => 'Badges',
        'description' => 'Manage your badges',
        'empty' => 'There are no badges yet.',
        'search' => 'Search',
        'search_placeholder' => 'Search for badges by name or description',
        'perPage' => 'Per Page',
        'filter' => 'Filter',
        'actions' => [
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
        'headers' => [
            'name' => 'Name',
            'description' => 'Description',
        ],
    ],

    'create' => [
        'title' => 'Create Badge',
        'description' => 'Create a new badge',
    ],

    'image' => [
        'title' => 'Image',
        'description' => 'Upload an image for this badge',
        'select' => 'Select an image',
        'change' => 'Change image',
        'remove' => 'Remove image',
        'label' => 'Image',
    ],

    'form' => [
        'name' => 'Name',
        'description' => 'Description',
        'update_btn' => 'Update Badge',
        'create_btn' => 'Create Badge',
    ],
    'edit' => 'Edit',
    'request' => [
        'created' => 'Badge Requested successfully.',
        'updated' => 'Badge Request updated successfully.',
    ],
];

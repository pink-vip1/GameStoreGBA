<?php
if (!defined('ABSPATH')) exit;

function gamestore_add_roles() {
    add_role(
        'game_developer',
        'Game Developer',
        [
            'read' => true,
            'upload_files' => true,
        ]
    );
}
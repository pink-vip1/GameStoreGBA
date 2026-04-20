<?php
if (!defined('ABSPATH')) exit;

function gamestore_get_game_meta_fields() {
    return [
        '_gamestore_short_description',
        '_gamestore_rom_path',
        '_gamestore_status',
        '_gamestore_reject_reason',
    ];
}
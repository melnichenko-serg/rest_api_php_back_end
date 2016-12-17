<?php

return [
    'text' => [
        "mime" => [
            "text/plain",
            "application/msword",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
            "application/vnd.ms-word.document.macroEnabled.12",
            "application/vnd.ms-excel.sheet.macroEnabled.12",
        ],
        "ext" => ["txt", "doc", "docx", "docm", "dotx", "dotm", "xls", "xlsx", "xlt", "xla"],
    ],

    'image' => [
        "mime" => [
            "image/jpeg",
            "image/gif",
            "image/png",
            "image/svg+xml",
            "image/tiff"
        ],
        "ext" => ["jpeg", "jpg", "gif", "png", "svg", "tiff",],
    ],
];

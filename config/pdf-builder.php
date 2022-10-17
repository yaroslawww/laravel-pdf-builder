<?php

return [
    'templates-storage' => env('PDF_BUILDER_TEMPLATES_STORAGE', env('FILESYSTEM_DRIVER', 'local')),
];

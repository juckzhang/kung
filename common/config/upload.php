<?php
use common\constants\CodeConstant;
return [
    'poster' => [
        'extensions' => null,
        'mimeTypes'  => null,
        'minSize' => null,
        'maxSize' => 10 * 1048576,
        'uploadRequired' => CodeConstant::UPLOAD_FILE_REQUIRED_ERROR,
        'tooBig'  => CodeConstant::UPLOAD_FILE_SIZE_BIG,
        'tooSmall' => CodeConstant::UPLOAD_FILE_SIZE_SMALL,
        'tooMany' => CodeConstant::UPLOAD_FILE_TOO_MANY,
        'wrongExtension' => CodeConstant::UPLOAD_FILE_EXTENSION_ERROR,
        'wrongMimeType' => CodeConstant::UPLOAD_FILE_MIME_ERROR,
        'dir' => realpath(__DIR__ . '/../../frontend/web/'),
        'path'  => 'upload/media-poster/',
        'url'   => 'http://localhost/',
        'remoteUpload' => false,
        'recursive' => false,
    ],
];
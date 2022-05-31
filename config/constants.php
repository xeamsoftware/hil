<?php

return [

    'uploadPaths' => [
        'uploadPic' => public_path()."/uploads/profilePics/",
        'uploadAppliedLeaveDocument' => public_path()."/uploads/appliedLeaveDocuments/",
        'profilePic' => env('APP_URL')."/uploads/profilePics/",
        'appliedLeaveDocument' => env('APP_URL')."/uploads/appliedLeaveDocuments/"
    ],

    'static' => [
        'profilePic' => env('APP_URL')."/admin_assets/static_assets/userPic.png",
        'maintenanceBg' => env('APP_URL')."/admin_assets/static_assets/maintenanceBg.jpg",
    ]

];

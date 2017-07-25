<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'imgUrl' =>'http://img.yiishop.com:8080/',
    'qiniu'=> [
        'accessKey'=>'m6Cij3uCegv1LPAeisbf8Z8V6UeGGuI57GveWpaf',
        'secretKey'=>'gqsFqBaRGmKw_WnAUg0sSETI4zqPjLeIqoWPGPyI',
        'domain'=>'http://otg2gkx20.bkt.clouddn.com/',
        'bucket'=>'yiishop',
        'area'=>\flyok666\qiniu\Qiniu::AREA_HUADONG
    ],
];

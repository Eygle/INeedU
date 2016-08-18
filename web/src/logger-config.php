<?php
return array(
    'rootLogger' => array(
        'appenders' => array('default'),
        'level'     => 'trace'
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderRollingFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date %logger %-5level %msg%n'
                )
            ),
            'params' => array(
                'file'              => $_SERVER['DOCUMENT_ROOT'].'/logs/logs.log',
                'append'            => true,
                'maxFileSize'       => '5MB',
                'maxBackupIndex'    => '5'
            )
        ),
    )
);
?>
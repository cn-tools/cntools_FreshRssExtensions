<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Αντιγραφή στο πρόχειρο',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Επιλέξτε',
                'desc' => '',
                'options' => array(
                    'default' => 'Προεπιλογή',
                    'color' => 'Χρώμα',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Ένας σύνδεσμος αντιγράφηκε',
            'good_more' => '#count# οι σύνδεσμοι αντιγράφηκαν',
            'bad' => 'Παρουσιάστηκε σφάλμα κατά την αντιγραφή!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`required at least FreshRSS version `%s`. (Χρησιμοποιείτε FreshRSS version `%s`)',
        ),
    ),
);

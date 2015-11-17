<?php

    /* URL constants for Barion API communication */

    DEFINE("BARION_API_URL_PROD", "https://api.barion.com/");
    DEFINE("BARION_WEB_URL_PROD", "https://secure.barion.com/Pay");
    DEFINE("BARION_API_URL_TEST", "https://api.test.barion.com/");
    DEFINE("BARION_WEB_URL_TEST", "https://secure.test.barion.com/Pay");
    
    DEFINE("API_ENDPOINT_PREPAREPAYMENT", "/Payment/Start");
    DEFINE("API_ENDPOINT_PAYMENTSTATE", "/Payment/GetPaymentState");
    DEFINE("API_ENDPOINT_QRCODE", "/QR/Generate");
    DEFINE("API_ENDPOINT_REFUND", "/Payment/Refund");
    DEFINE("API_ENDPOINT_FINISHRESERVATION", "/Payment/FinishReservation");
    
    DEFINE("PAYMENT_URL", "/Pay");

?>

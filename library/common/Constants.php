<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/* URL constants for Barion API communication */

DEFINE("BARION_API_URL_PROD", "https://api.barion.com");
DEFINE("BARION_WEB_URL_PROD", "https://secure.barion.com/Pay");
DEFINE("BARION_API_URL_TEST", "https://api.test.barion.com");
DEFINE("BARION_WEB_URL_TEST", "https://secure.test.barion.com/Pay");

DEFINE("API_ENDPOINT_PREPAREPAYMENT", "/Payment/Start");
DEFINE("API_ENDPOINT_PAYMENTSTATE", "/Payment/GetPaymentState");
DEFINE("API_ENDPOINT_QRCODE", "/QR/Generate");
DEFINE("API_ENDPOINT_REFUND", "/Payment/Refund");
DEFINE("API_ENDPOINT_FINISHRESERVATION", "/Payment/FinishReservation");
DEFINE("API_ENDPOINT_CAPTURE", "/Payment/Capture");
DEFINE("API_ENDPOINT_CANCELAUTHORIZATION", "/Payment/CancelAuthorization");
DEFINE("API_ENDPOINT_3DS_COMPLETE", "/Payment/Complete");

DEFINE("PAYMENT_URL", "/Pay");
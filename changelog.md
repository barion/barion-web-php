# Changelog
### [v1.4.2](https://github.com/barion/barion-web-php/releases/tag/v1.4.2) 2019-08-15
- ADD: added RecurrenceType and ChallengePreference 3DS properties

### [v1.4.1](https://github.com/barion/barion-web-php/releases/tag/v1.4.1) 2019-08-14
- FIX: fixed shipping address model parameters
- ADD: detailed documentation for different payment scenarios

### [v1.4.0](https://github.com/barion/barion-web-php/releases/tag/v1.4.0) 2019-08-08
- ADD: supporting payment properties related to 3D Secure authentication
- ADD: support for Delayed Capture payment scenarios

### v1.3.2  2019-08-05
- FIX: added shipping address model and fixed shipping address structure in examples

### [v1.3.1](https://github.com/barion/barion-web-php/releases/tag/v1.3.1) 2019-03-20
- ADD: Greek locale support (el-GR)

### [v1.3](https://github.com/barion/barion-web-php/releases/tag/v1.3) 2019-03-12
- ADD: CZK currency and czech locale

### [v1.2.9](https://github.com/barion/barion-web-php/releases/tag/v1.2.9) 2017-05-16
- FIX: PaymenStateResponse extended to parse all the available fields

### [v1.2.8](https://github.com/barion/barion-web-php/releases/tag/v1.2.8)  2017-04-13
- FIX:  Refunded transactions parsed correctly

### [v1.2.7](https://github.com/barion/barion-web-php/releases/tag/v1.2.7)  2017-02-14
- MERGE: Added FundingInformation and BankCard to PaymentStateResponse
- MERGE: Added currency to PaymentStateResponseModel and TransactionDetailModel
- Added "Expired", "PartiallySucceeded" and "InProgress" payment states to enumeration

### v1.2.5  2016-11-07
- MERGE: Added currency handling to the start payment request model to handle EUR/USD currencies

### v1.2.4  2016-05-25
- MERGE: Added parent constructor call to ResponseModels to get the Errors array initialized properly

### [v1.2.3](https://github.com/barion/barion-web-php/releases/tag/v1.2.3) 2016-01-14
- FIX: Extra option for environments with SSL problems
- FIX: Some issues with the models (finish reservation and QR models)

### v1.2.1  2016-01-11
- FIX: Adding DIRECTORY_SEPARATOR to the path construction (thx to @zelding, based on: https://github.com/barion/barion-web-php/pull/1)

### v1.2.0  2016-01-11
- SSL certificates included for the TEST environment
- cURL errors are transferred to the client to help debugging
- Removed end php tags
- Added licencing headers

### v1.1.0 2015-11-27
- Library class "Locale" renamed to "UILocale"
- Minimized notices from uninitialized indices

### v1.0.1 2015-11-26
- Fixed minor include path issues

### v1.0.0 2015-11-17
- Initial release

----------------------------------------
© 2017 Barion Payment Inc.
All rights reserved.

# Changelog

## 0.5.0

- Added configurable `checkout_route` to `payit.php` for failed/cancelled payment redirects
- Updated success, cancelled and approval controllers to use config-based route redirects
- Improved initiate payment validation with dynamic platform ID checks and stricter input rules
- Updated payment platform resolution/session storage to use validated `platformId` values

## 0.4.0

- Change `handlePayment` to `initiatePayment` to be more descriptive
- Added standalone PayPal component
- Updated controllers to single action controllers

## 0.3.0

- Added `PaymentService` abstract class and updated services to extend it
- Added `payit.php` config file and updated services to use it
- Removed icons components (will be replaced with GT icons)
- Removed migration, factory and seeder (use sushi instead)

## 0.2.0

- Moved controllers to http directory
- Updated minor changes to payment controller to be more concise

## 0.1.0

- fix psr-4 naming problem
- minor template fixes

## 0.0.1

- initial release

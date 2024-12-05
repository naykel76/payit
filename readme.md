<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/dt/naykel/payit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/v/naykel/payit" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/l/naykel/payit" alt="License"></a>

# NAYKEL Payment Management Package

- Add check to make sure .env is set


- update the `resolve` method in the `PaymentPlatformResolver` to be a more updated
  Laravel way of resolving the platform.
- remove auth login used for development in layouts

1. Route fires the handle payment method passing in the amount
2. Create and return the payment intent.
    - The PaymentIntent includes a client secret that the client side uses to securely complete the payment process.


What to Do After Creating a PaymentIntent
Store the PaymentIntent ID: You need to store the PaymentIntent ID somewhere safe (e.g., in a session or database) because it will be used later to confirm the payment. This is useful in case you need to retrieve or confirm the payment later.

Send the client_secret to the Frontend: The client_secret is required on the client side to confirm the payment and to allow Stripe to communicate with the client. The frontend will use this client_secret to confirm the payment, either via Stripe Elements or Stripe's hosted Checkout page.

Confirm the Payment: Once the PaymentIntent is created, you’ll either confirm it on the client side or backend side (based on your flow). If using the client-side approach, Stripe will handle any 3D Secure or authentication challenges. If you're processing everything backend-side, you can confirm the payment manually.
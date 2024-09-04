<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/dt/naykel/payit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/v/naykel/payit" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/l/naykel/payit" alt="License"></a>

# NAYKEL Payment Management Package

- [User checkout and payment process flowchart](#user-checkout-and-payment-process-flowchart)
- [Sequence Diagrams](#sequence-diagrams)
  - [Sequence diagram for payment options in checkout process](#sequence-diagram-for-payment-options-in-checkout-process)
  - [Sequence diagram for stipe credit card payment](#sequence-diagram-for-stipe-credit-card-payment)

`ppid` Payment Platform ID

## User checkout and payment process flowchart

The user must be logged in to proceed with the checkout process. If the user is not logged in, they
are redirected to the login page. Once logged in, the user can proceed to the checkout page. 

```mermaid
graph LR
    Start[Click <br> Checkout] --> IsLoggedIn{Logged in?}
    IsLoggedIn -->|Yes| DisplayCheckout[Display checkout]
    IsLoggedIn -->|No| RedirectToLogin[Redirect to login]
    RedirectToLogin --> Login[User logs in]
    Login --> DisplayCheckout
```

```mermaid
graph TB

    SelectPaymentMethod[Select payment method] -->|Credit Card| DisplayCardComponent[Display credit <br> card component]
    SelectPaymentMethod[Select payment method] -->|PayPal| PayPal[Display redirect <br> to PayPal message]

    DisplayCardComponent --> EnterCardDetails

    SelectPaymentMethod --> h[Proceed to payment]
    --> H{Is payment successful?}
    H -->|Yes| I[Display success message]
    H -->|No| J[Display payment error]
    J --> F
    I --> K[End]
```

## Sequence Diagrams

### Sequence diagram for payment options in checkout process

This diagram illustrates the sequence of events in the checkout to display the available payment
options. It shows how the system, specifically the `PaymentOptions` component, fetches active
payment methods from the `PaymentPlatform` model (which represents data in the database). These
payment methods are then presented to the user in the Checkout view. The user can then select
their preferred payment method from the provided options for further processing.

** method is used for both the label, and to identify the collapse component to display on the front-end.

```mermaid
sequenceDiagram
    actor user
    participant view as Checkout<br><<view>>
    participant options as PaymentOptions<br><<Component>>
    participant model as PaymentPlatform<br><<Model>>

    user->>view: Click checkout
    activate view
    view->>options: Display payment-options component
        activate options
            options->>model: Fetch active payment platforms
            activate model
                model-->>options: Return active payment platforms
            deactivate model
            loop each platform
                options->>view: Display payment option radio and label
                view->>user: Show payment option
            end
            user->>options: Select payment option (method)
            options->>view: Toggle the selected option's component
        deactivate options
            view->>user: Collapse and display the <br> payment-method's component
    deactivate view
```

This diagram assumes you are logged, on the checkout page about to select a payment method.

```mermaid
sequenceDiagram
    actor user
    participant options as PaymentOptions<br><<Component>>
    participant controller as pay()<br><<PaymentController>>
    participant resolve as PaymentPlatformResolver
    participant service as PaymentService<br><<Abstract Class>>

    user->>options: Select payment option (method)
    note right of options: Refer to credit card sequence  when 'Stripe' <br> is selected as the payment method.
    options->>user: Collapse and display the <br> payment-method's component
    user->>user: Enter payment details
    user->>controller: Click process payment (request)
    controller->>controller: Validate platform and terms
    alt is valid?
        controller->>resolve: Resolve payment service (ppid)
        resolve-->>controller: Return payment service. eg. paypal, stripe...
        controller->>service: Instantiate corresponding PaymentService
        service->>service: handlePayment()
        service->>service: handleApproval()
        controller->>options: Display payment-options component
    else is not valid?
        controller->>user: Display error message
    end
```

### Sequence diagram for stipe credit card payment

** `paymentForm` is the id of the form element in the DOM

```mermaid
sequenceDiagram
    actor user as User
    participant component as CreditCard View<br><<Component>>
    participant stripe as Stripe
    participant form as Form

    user->>component: Selects Stripe as payment method
    component->>stripe: Initializes Stripe (public_key)
    stripe-->>component: Returns Stripe instance
    component->>stripe: Create stripe.elements instance
    stripe-->>component: Returns Elements instance
    component->>stripe: Calls elements.create('card') <br>to create Card Element
    stripe-->>component: Returns Card Element
    component->>component: Mount Card Element in DOM
    component->>user: Displays credit card form
    component->>form: Get form element by id (paymentForm)
    form-->>component: Returns form instance
    component->>form: Get button by id (payButton)
    form-->>component: Returns button instance
    component->>form: Adds click event listener to button
    user->>user: Enters credit card details
    user->>form: Clicks payment button
    form->>stripe: Calls stripe.createPaymentMethod with card details
    stripe-->>form: Returns paymentMethod or error
    alt error
        form->>component: Display error message
        component->>user: Shows error message
    else no error
        form->>component: Get tokenInput by id 'paymentMethod'
        component-->>form: Returns tokenInput instance
        form->>component: Set value of tokenInput to paymentMethod.id
        component-->>form: Returns updated tokenInput
        form->>form: Calls form.submit() to submit the form
    end
```






```mermaid
classDiagram
    class PaymentController {
        -PaymentPlatformResolver paymentPlatformResolver
        +pay(Request request) : Response
        +approval() : Response
        +cancelled() : Response
        +confirmed() : Response
    }
    class Controller
    class PaymentPlatformResolver
    class Request
    class Response
    PaymentController --|> Controller
    PaymentController --> PaymentPlatformResolver : Uses
    PaymentController --> Request : Uses
    PaymentController --> Response : Returns
```

```mermaid
sequenceDiagram
    participant User
    participant PaymentController
    participant PaymentPlatformResolver
    participant PaymentService
    participant PayPalService
    participant StripeService
    participant PaymentPlatformModel
    participant PaymentPlatformTable
    participant ConsumesExternalServices

    User->>PaymentController: Initiates payment


    
    PaymentController->>PaymentPlatformResolver: Resolve payment platform
    PaymentPlatformResolver->>PaymentPlatformModel: Query platform by ID
    PaymentPlatformModel->>PaymentPlatformTable: Fetch platform details
    PaymentPlatformTable-->>PaymentPlatformModel: Return platform details
    PaymentPlatformModel-->>PaymentPlatformResolver: Return platform details
    PaymentPlatformResolver->>PaymentService: Resolve service class
    PaymentService->>PayPalService: If platform is PayPal
    PaymentService->>StripeService: If platform is Stripe
    PaymentService->>ConsumesExternalServices: Use external services trait
    ConsumesExternalServices->>PaymentService: Make HTTP request
    PaymentService-->>PaymentController: Return payment service instance
    PaymentController-->>User: Proceed with payment
```

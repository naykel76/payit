<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/dt/naykel/payit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/v/naykel/payit" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/l/naykel/payit" alt="License"></a>

# NAYKEL Payment Management Package

<!-- TOC -->

- [Entity Relationship Diagram](#entity-relationship-diagram)
- [User checkout and payment process flowchart](#user-checkout-and-payment-process-flowchart)
- [Sequence Diagrams](#sequence-diagrams)
    - [Sequence diagram for payment options in checkout process](#sequence-diagram-for-payment-options-in-checkout-process)
    - [Sequence diagram for stipe credit card payment](#sequence-diagram-for-stipe-credit-card-payment)

<!-- /TOC -->

`ppid` Payment Platform ID

<a id="markdown-entity-relationship-diagram" name="entity-relationship-diagram"></a>

## Entity Relationship Diagram

```mermaid
erDiagram
    PAYMENTS {
        bigint id PK
        string platform_name "PayPal, Stripe, etc"
        string method "Credit Card, PayPal, express etc"
        boolean active
    }
```

**method** is used for both the label, and to identify the collapse component to display on the front-end.

The name `method` may be a bit deceiving as it is used to identify the component to display on the
front-end as well as the label for the payment method.

For example, if the method is 'Credit Card' then the label for the payment method will be 'Credit
Card' and the component to be displayed will be `credit-card`.


<a id="markdown-user-checkout-and-payment-process-flowchart" name="user-checkout-and-payment-process-flowchart"></a>

## User checkout and payment process flowchart

```mermaid
graph TB
    A[Start] --> B{Is user logged in?}
    B -->|Yes| C[Display checkout view]
    B -->|No| D[Redirect to login]
    D --> E[User logs in]
    E --> C
    C --> F[User selects payment method]
    F --> G[Proceed to payment]
    G --> H{Is payment successful?}
    H -->|Yes| I[Display success message]
    H -->|No| J[Display payment error]
    J --> F
    I --> K[End]
```

<a id="markdown-sequence-diagrams" name="sequence-diagrams"></a>

## Sequence Diagrams

<a id="markdown-sequence-diagram-for-payment-options-in-checkout-process" name="sequence-diagram-for-payment-options-in-checkout-process"></a>

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

---
---
---
---
---
---
---



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

<a id="markdown-sequence-diagram-for-stipe-credit-card-payment" name="sequence-diagram-for-stipe-credit-card-payment"></a>

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


---
---
---
---
---
---
---
---

```mermaid
classDiagram
    PaymentOptions --|> Component : Extends
    class PaymentOptions {
        +bool v2
        +__construct(bool)
        +render() : view
    }
    class Component {
    }
    PaymentPlatform -- PaymentOptions : Uses
    class PaymentPlatform {
        +where(string, bool) : PaymentPlatform
        +get() : Collection
    }
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

# Stripe Payment Setup Guide

## Payment System Overview

Your application uses **Stripe** for credit card payments. The system supports two payment methods:
1. **Credit Card** - Processed through Stripe
2. **Cash on Delivery** - No payment processing needed

## Stripe Test/Demo Credentials

For testing purposes, you can use Stripe's test mode with the following demo credentials:

### Test Card Numbers

Use these test card numbers in your checkout:

**Successful Payment:**
- Card Number: `4242 4242 4242 4242`
- Expiry: Any future date (e.g., `12/25`)
- CVC: Any 3 digits (e.g., `123`)
- ZIP: Any 5 digits (e.g., `12345`)

**Card Declined:**
- Card Number: `4000 0000 0000 0002`

**Insufficient Funds:**
- Card Number: `4000 0000 0000 9995`

**Requires Authentication (3D Secure):**
- Card Number: `4000 0027 6000 3184`

### API Keys Setup

You need to add these to your `.env` file:

```env
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
```

## Getting Your Stripe Test Keys

1. Go to [https://dashboard.stripe.com/register](https://dashboard.stripe.com/register)
2. Create a free Stripe account (or log in)
3. Make sure you're in **Test Mode** (toggle in the top right)
4. Go to **Developers** → **API keys**
5. Copy your **Publishable key** (starts with `pk_test_`) → This is `STRIPE_KEY`
6. Copy your **Secret key** (starts with `sk_test_`) → This is `STRIPE_SECRET`

## Important Notes

1. **Currency**: The payment system is currently set to **JPY (Japanese Yen)**. Since you're in the US, you may want to change it to **USD**.

2. **Test Mode vs Live Mode**:
   - **Test Mode**: Use `pk_test_` and `sk_test_` keys (for development/testing)
   - **Live Mode**: Use `pk_live_` and `sk_live_` keys (for production)

3. **Security**: Never commit your `.env` file or expose your secret keys publicly.

## Configuration Steps

1. Open your `.env` file in the project root
2. Add or update these lines:
   ```env
   STRIPE_KEY=your_publishable_key_here
   STRIPE_SECRET=your_secret_key_here
   ```
3. Save the file
4. Clear config cache: `php artisan config:clear`
5. Test a payment using the test card numbers above

## Currency Configuration

Currently, payments are processed in **JPY (Japanese Yen)**. To change to **USD (US Dollar)**:

1. Open `app/Http/Controllers/OrderController.php`
2. Find line 167 where it says `'currency' => 'jpy',`
3. Change it to `'currency' => 'usd',`
4. Save the file

## Testing Payment Flow

1. Add items to cart
2. Go to checkout
3. Select "Credit Card" as payment method
4. Use test card: `4242 4242 4242 4242`
5. Fill in any future expiry date and any CVC/ZIP
6. Complete the order

## Support

- Stripe Documentation: https://stripe.com/docs
- Stripe Test Cards: https://stripe.com/docs/testing
- Stripe Dashboard: https://dashboard.stripe.com/test/dashboard


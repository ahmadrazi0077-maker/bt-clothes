<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        $email = strtolower(trim($validated['email']));

        $shop = config('shopify.store');
        $token = config('shopify.storefront_token');
        $version = config('shopify.api_version');

        if (!$shop || !$token) {
            return back()->with(
                'newsletter_error',
                'Newsletter service is currently unavailable.'
            );
        }

        $endpoint = "https://{$shop}/api/{$version}/graphql.json";

        $query = <<<'GRAPHQL'
mutation customerCreate($input: CustomerCreateInput!) {
    customerCreate(input: $input) {
        customer {
            id
            email
        }
        customerUserErrors {
            field
            message
            code
        }
    }
}
GRAPHQL;

        try {

            $response = Http::withHeaders([
                'X-Shopify-Storefront-Access-Token' => $token,
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'query' => $query,
                'variables' => [
                    'input' => [
                        'email' => $email,
                        'emailMarketingConsent' => [
                            'marketingState' => 'SUBSCRIBED',
                        ],
                    ],
                ],
            ]);

            if (!$response->successful()) {

                Log::error('Shopify Newsletter HTTP Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return back()->with(
                    'newsletter_error',
                    'Unable to subscribe right now. Please try again.'
                );
            }

            $data = $response->json();

            if (!empty($data['errors'])) {

                Log::error('Shopify Newsletter GraphQL Error', [
                    'errors' => $data['errors'],
                ]);

                return back()->with(
                    'newsletter_error',
                    'Unable to subscribe right now.'
                );
            }

            $customerErrors =
                $data['data']['customerCreate']['customerUserErrors'] ?? [];

            if (!empty($customerErrors)) {

                $message = $customerErrors[0]['message']
                    ?? 'Unable to subscribe.';

                /*
                 * If the email already exists, don't show
                 * a confusing technical error to the customer.
                 */
                if (
                    str_contains(strtolower($message), 'already') ||
                    str_contains(strtolower($message), 'exists')
                ) {
                    return back()->with(
                        'newsletter_success',
                        'You are already subscribed to our newsletter!'
                    );
                }

                Log::warning('Shopify Customer Error', [
                    'errors' => $customerErrors,
                ]);

                return back()->with(
                    'newsletter_error',
                    $message
                );
            }

            return back()->with(
                'newsletter_success',
                'Thank you! You are now subscribed to our newsletter.'
            );

        } catch (\Throwable $e) {

            Log::error('Newsletter Exception', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'newsletter_error',
                'Something went wrong. Please try again later.'
            );
        }
    }
}
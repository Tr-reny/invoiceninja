<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2019. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\DataMapper;

class PaymentTransaction
{

	public $transaction_id;

	public $gateway_response;

	public $account_gateway_id;

	public $payment_type_id;

	public $status; // prepayment|payment|response|completed

	public $invoices;

}
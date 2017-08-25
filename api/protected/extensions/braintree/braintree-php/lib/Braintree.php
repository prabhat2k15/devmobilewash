<?php
/**
 * Braintree PHP Library
 * Creates class_aliases for old class names replaced by PSR-4 Namespaces
 *
 * @copyright  2015 Braintree, a division of PayPal, Inc.
 */

include_once('autoload.php');

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new Braintree_Exception('PHP version >= 5.4.0 required');
}
require_once('Braintree/Modification.php');
require_once('Braintree/Instance.php');
require_once('Braintree/Address.php');
require_once('Braintree/AddOn.php');
require_once('Braintree/Collection.php');
require_once('Braintree/Configuration.php');
require_once('Braintree/CreditCard.php');
require_once('Braintree/ClientToken.php');
require_once('Braintree/Customer.php');
require_once('Braintree/CustomerSearch.php');
require_once('Braintree/DisbursementDetails.php');
require_once('Braintree/PaymentMethod.php');
require_once('Braintree/Descriptor.php');
require_once('Braintree/Digest.php');
require_once('Braintree/Discount.php');
require_once('Braintree/IsNode.php');
require_once('Braintree/EqualityNode.php');
require_once('Braintree/Exception.php');
require_once('Braintree/Http.php');
require_once('Braintree/KeyValueNode.php');
require_once('Braintree/MerchantAccount.php');
require_once('Braintree/MerchantAccount/BusinessDetails.php');
require_once('Braintree/MerchantAccount/FundingDetails.php');
require_once('Braintree/MerchantAccount/IndividualDetails.php');
require_once('Braintree/MerchantAccount/AddressDetails.php');
require_once('Braintree/MultipleValueNode.php');
require_once('Braintree/MultipleValueOrTextNode.php');
require_once('Braintree/PartialMatchNode.php');
require_once('Braintree/Plan.php');
require_once('Braintree/RangeNode.php');
require_once('Braintree/ResourceCollection.php');
require_once('Braintree/SettlementBatchSummary.php');
require_once('Braintree/Subscription.php');
require_once('Braintree/SubscriptionSearch.php');
require_once('Braintree/TextNode.php');
require_once('Braintree/Transaction.php');
require_once('Braintree/Disbursement.php');
require_once('Braintree/TransactionSearch.php');
require_once('Braintree/TransparentRedirect.php');
require_once('Braintree/Util.php');
require_once('Braintree/Version.php');
require_once('Braintree/Xml.php');
require_once('Braintree/Error/Codes.php');
require_once('Braintree/Error/ErrorCollection.php');
require_once('Braintree/Error/Validation.php');
require_once('Braintree/Error/ValidationErrorCollection.php');
require_once('Braintree/Exception/Authentication.php');
require_once('Braintree/Exception/Authorization.php');
require_once('Braintree/Exception/Configuration.php');
require_once('Braintree/Exception/DownForMaintenance.php');
require_once('Braintree/Exception/ForgedQueryString.php');
require_once('Braintree/Exception/InvalidSignature.php');
require_once('Braintree/Exception/NotFound.php');
require_once('Braintree/Exception/ServerError.php');
require_once('Braintree/Exception/SSLCertificate.php');
require_once('Braintree/Exception/SSLCaFileNotFound.php');
require_once('Braintree/Exception/Unexpected.php');
require_once('Braintree/Exception/UpgradeRequired.php');
require_once('Braintree/Exception/ValidationsFailed.php');
require_once('Braintree/Result/CreditCardVerification.php');
require_once('Braintree/Result/Error.php');
require_once('Braintree/Result/Successful.php');
require_once('Braintree/Test/CreditCardNumbers.php');
require_once('Braintree/Test/MerchantAccount.php');
require_once('Braintree/Test/TransactionAmounts.php');
require_once('Braintree/Test/VenmoSdk.php');
require_once('Braintree/Transaction/AddressDetails.php');
require_once('Braintree/Transaction/CreditCardDetails.php');
require_once('Braintree/Transaction/CustomerDetails.php');
require_once('Braintree/Transaction/StatusDetails.php');
require_once('Braintree/Transaction/SubscriptionDetails.php');
require_once('Braintree/WebhookNotification.php');
require_once('Braintree/WebhookTesting.php');
require_once('Braintree/Xml/Generator.php');
require_once('Braintree/Xml/Parser.php');
require_once('Braintree/CreditCardVerification.php');
require_once('Braintree/CreditCardVerificationSearch.php');
require_once('Braintree/PartnerMerchant.php');


function requireDependencies() {
    $requiredExtensions = ['xmlwriter', 'openssl', 'dom', 'hash', 'curl'];
    foreach ($requiredExtensions AS $ext) {
        if (!extension_loaded($ext)) {
            throw new Braintree_Exception('The Braintree library requires the ' . $ext . ' extension.');
        }
    }
}

requireDependencies();